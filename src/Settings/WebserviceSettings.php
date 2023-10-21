<?php

namespace Savannabits\Saas\Settings;

use Illuminate\Support\Str;
use Spatie\LaravelSettings\Settings;

class WebserviceSettings extends Settings
{
    public ?string $base_url;

    public ?string $staff_endpoint;

    public ?string $staff_by_username_endpoint;

    public ?string $student_endpoint;

    public ?string $all_staff_endpoint;

    public ?string $all_current_students_endpoint;

    public ?string $all_active_students_endpoint;

    public ?string $all_departments_endpoint;

    public static function group(): string
    {
        return 'webservice';
    }

    public function makeUrl(string $endpoint, array $params = []): string
    {
        $path = Str::of($endpoint)->ltrim('/')
            ->replace(
                collect($params)->keys()->map(fn ($param) => "{{$param}}")->toArray(),
                collect($params)->values()->toArray()
            )->toString();

        return \Str::of($this->base_url)->rtrim('/')->append('/')->append($path)->toString();
    }

    public function getStaffByUsernameUrl($username): string
    {
        return $this->makeUrl($this->staff_by_username_endpoint, ['username' => trim($username)]);
    }
    public function getStaffByNumberUrl(string $staff_number): string
    {
        return $this->makeUrl($this->staff_endpoint, ['staff_number' => trim($staff_number)]);
    }


    public function getStudentUrl($studentNumber): string
    {
        return $this->makeUrl($this->student_endpoint, ['studentNo' => trim($studentNumber)]);
    }

    public function getAllStaffUrl(): string
    {
        return $this->makeUrl($this->all_staff_endpoint);
    }

    public function getAllDepartmentsUrl(): string
    {
        return $this->makeUrl($this->all_departments_endpoint);
    }

    public function getAllActiveStudentsUrl(): string
    {
        return $this->makeUrl($this->all_active_students_endpoint);
    }

    public function getAllCurrentStudentsUrl(): string
    {
        return $this->makeUrl($this->all_current_students_endpoint);
    }
}
