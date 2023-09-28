<?php

namespace Savannabits\Saas\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Savannabits\Saas\Helpers\Ws;

class Webservice
{
    public static function make(): Webservice
    {
        return new self();
    }

    /**
     * @throws RequestException
     */
    public function fetchStudent(string | int $studentNo): Collection
    {
        return Http::withoutVerifying()->asJson()->acceptJson()->get(Ws::settings()->getStudentUrl($studentNo))->throw()->collect();
    }

    /**
     * @throws RequestException
     */
    public function fetchStaff(string $username): Collection
    {
        return Http::withoutVerifying()->asJson()->acceptJson()->get(Ws::settings()->getStaffUrl($username))->throw()->collect();
    }

    /**
     * @throws RequestException
     */
    public function fetchDepartments(): Collection
    {
        return Http::withoutVerifying()->asJson()->acceptJson()->get(Ws::settings()->getAllDepartmentsUrl())->throw()->collect();
    }
}
