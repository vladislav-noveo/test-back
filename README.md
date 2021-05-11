# Joovence back-end test

This is the back-end test for your Joovence application. The goal is to evaluate your capabilities on creating an API and how you structure your code.

## Instructions

You are about to code an API to handle bookings for patients. You are asked to implement the following :

-   Get the list of doctors
-   Get the list of availabilities for a doctor
-   Get the list of bookings for the logged in user
-   Create a booking for the logged in user
-   Cancel a booking the logged in user

## How to submit

-   Fork the repository
-   Create a branch `submission`
-   Create a pull request to `master` on your forked repository

## Installation

The easiest way to launch this project is by using [Laravel Sail](https://laravel.com/docs/8.x/sail#introduction).

-   `composer install`
-   `cp .env.example .env`
-   `./vendor/bin/sail up`
-   `./vendor/bin/sail artisan key:generate`
-   `./vendor/bin/sail artisan migrate:fresh --seed`

> You are free to use another way to launch the project.

## Models

`Doctor` represents a doctor

-   `name` represents the name of the doctor
-   `agenda` represents the way a doctor handles availabilities. Possible values are :
    -   `database` (default) for a doctor who uses database for availabilities
    -   `doctolib` for a doctor who uses **Doctolib** Check `External agendas` section for more information
    -   `clicrdv` for a doctor who uses **ClicRDV**. Check `External agendas` section for more information
-   `external_agenda_id` (nullable) represents the id of the external agenda

`Availability` represents a doctor's availability when `agenda` is `database`

-   `doctor_id` represents the doctor's id
-   `start` represents the beginning of the availability
-   `end` represents the end of the availability

`Booking` represents a booking

-   `user_id` represents the user's id
-   `doctor_id` represents the doctor's id
-   `date` represents the date and time of the booking
-   `status` represents the status of the booking. Possible values are :
    -   `confirmed` (default) when the booking is created
    -   `canceled` when the booking in canceled

## External agendas

Here are the endpoints to fetch availabilities for external agendas

| Service  | Endpoint                                                                                                                                                             |
| -------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Doctolib | GET [https://tech-test.joovence.dev/api/doctolib/{EXTERNAL_ID}/availabilities](https://tech-test.joovence.dev/api/doctolib/{EXTERNAL_ID}/availabilities)             |
| ClicRDV  | GET [https://tech-test.joovence.dev/api/clic-rdv/availabilities?proId={EXTERNAL_ID}](https://tech-test.joovence.dev/api/clic-rdv/availabilities?proId={EXTERNAL_ID}) |

## Create the following endpoints

### Doctors

-   `GET /doctors`
    -   **Response** : list of doctors
    -   A doctor should have the following attributes :

| Attribute | Description              |
| --------- | ------------------------ |
| `id`      | identifier of the doctor |
| `name`    | name of the doctor       |

### Availabilities

-   `GET /doctors/{doctorId}/availabilities`
    -   **Response** : list of availabilities
    -   An availability should have the following attributes :

| Attribute | Description                                   |
| --------- | --------------------------------------------- |
| `start`   | DateTime of the beginning of the availability |

### Bookings

-   `GET /bookings`
    -   **Response** : list of bookings
    -   The list should be ordered by `date` from most recent to most distant in time.
    -   A booking should have the following attributes :

| Attribute   | Description               |
| ----------- | ------------------------- |
| `id`        | identifier of the booking |
| `doctor_id` | identifier of the doctor  |
| `user_id`   | identifier of the user    |
| `date`      | DateTime of the booking   |
| `status`    | status of the booking     |

-   `POST /bookings`
    -   **Response** : the created booking
    -   The request should have the following parameters :

| Parameter   | Description              |
| ----------- | ------------------------ |
| `doctor_id` | identifier of the doctor |
| `date`      | DateTime of the booking  |

-   `GET /bookings/{bookingId}/cancel`
    -   **Response** : the canceled booking
