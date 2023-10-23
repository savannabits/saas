<?php

namespace Savannabits\Saas\Services;

use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use LdapRecord\Laravel\Commands\ImportLdapUsers;
use Savannabits\Saas\Ldap\Staff;
use Savannabits\Saas\Ldap\Student;

class Users
{
    public static function make(): static
    {
        return new static();
    }

    /**
     * @throws RequestException
     * @throws Exception
     */
    public function syncUser(string $username, string $provider, ?bool $skipLdapImport = false)
    {
        $user = \App\Models\User::whereUsername($username)->first();

        if ($user) {
            \Log::info("User exists. Setting GUID");
            if ($provider ==='staff') {
                $staff = Staff::query()->where('samaccountname','=', $user->username)->first();
                if ($staff) {
                    \Log::info("AD User found. Updating");
                    $user->update(['guid' => $staff->getConvertedGuid()]);
                }
            } else {
                $student = Student::query()->where('samaccountname','=', $user->username)->first();
                if ($student) {
                    \Log::info("AD Student User found. Updating");
                    $user->update(['guid' => $student->getConvertedGuid()]);
                }
            }
        }

        if (! $skipLdapImport) {
            $res = Artisan::call('ldap:import', [
                'provider' => $provider,
                'user' => $username,
                '--no-interaction',
                '--restore' => true,
                '--delete' => true,
            ]);
            if ($res === ImportLdapUsers::FAILURE) {
                throw new Exception('The import command failed.');
            } elseif ($res === ImportLdapUsers::INVALID) {
                throw new Exception('The options given could not be used for the import.');
            }
        }
        // Sync with DataService
        if ($provider === 'staff') {
            $staff = Webservice::make()->fetchStaff($username);
            /**
             * {
             * "payrollNo" => "3040"
             * "employeeNo" => "3040"
             * "username" => "smaosa"
             * "lastName" => "Maosa"
             * "firstName" => "Samson"
             * "middleName" => "Arosi"
             * "names" => "Maosa, Samson Arosi"
             * "genderId" => "1"
             * "gender" => "Male"
             * "categoryId" => "11"
             * "category" => "Administrative"
             * "jobStatusId" => "10"
             * "jobStatus" => "2 Year Contract"
             * "jobStatusType" => "ft"
             * "dateOfBirth" => "1989-10-20"
             * "departmentId" => "42"
             * "departmentShortName" => "ICTS"
             * "department" => "Information and Communication Technology Services"
             * "supervisorUsernames" => "mndeto"
             * "jobTitle" => "Assistant Manager, ICT Enterprise Application Services"
             * "email" => "smaosa@strathmore.edu"
             * "mobileNo" => "0708467001"
             * "mealsActive" => "1"
             * "mealsAllowance" => "4000"
             * "hodPayrollNo" => "315"
             * "hodUsername" => "smomanyi"
             * "delegatePayrollNo" => "1024"
             * "delegateUsername" => "bogutu"
             * }
             */
            if ($user?->id && $user->isDraft()) {
                if ($staff->count()) {
                    $user->update([
                        'user_number' => $userNumber = $staff->get('employeeNo'),
                        'code' => Str::padLeft($userNumber, 5, '0'),
                        'is_active' => true,
                        'first_name' => $staff->get('firstName'),
                        'other_names' => $staff->get('middleName'),
                        'surname' => $staff->get('lastName'),
                        'mobile_no' => $staff->get('mobileNo'),
                        'email' => $staff->get('email'),
                        'gender' => $staff->get('gender'),
                        'dob' => $staff->get('dateOfBirth'),
                        'department_short_name' => $staff->get('departmentShortName'),
                        'meals_active' => (bool) intval($staff->get('mealsActive')),
                        'meals_allowance' => floatval($staff->get('mealsAllowance')),
                    ]);
                } else {
                    $user->update([
                        'is_active' => false,
                        'meals_active' => false,
                        'meals_allowance' => 0.0,
                    ]);
                }
            }
        }

        return $user;
    }
}
