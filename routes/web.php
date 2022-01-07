<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

class Environment extends Model
{
    protected static $unguarded = true;
}

class Deployment extends Model
{
    protected static $unguarded = true;
}

class Project extends Model
{
    public function deployments()
    {
        return $this->hasManyThrough(Deployment::class, Environment::class);
    }
}


Route::get('/', function () {
    $project = Project::firstOr(function () {
        $project = Project::create();

        // User with ID 1 creates an environment.
        $environment = Environment::create([
            'user_id' => 1,
            'project_id' => $project->getKey(),
        ]);

        // User with ID 2 creates a deployment.
        Deployment::create([
            'user_id' => 2,
            'environment_id' => $environment->getKey(),
        ]);

        return $project;
    });

    /*
     * When set to `true`, this will update the select statement
     * from `SELECT *` to `SELECT deployments.*`
     * which will prevent from mixing up the values between the intermediate and final tables.
     */
    $workaroundEnabled = false;

    $projectDeployments = $project->deployments()
        ->when($workaroundEnabled, function ($query) {
            $query->select('deployments.*');
        })
        ->cursorPaginate();

    ddd(
        $projectDeployments->first()->user_id, // Should return 2.
    );
});
