<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Drone;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addUser(Request $request)
    {
        // Validate and add a new user
    }

    public function updateUser(Request $request, $id)
    {
        // Validate and update user information
    }

    public function deleteUser($id)
    {
        // Delete a user
    }

    public function addDrone(Request $request)
    {
        // Validate and add a new drone
    }

    public function updateDrone(Request $request, $id)
    {
        // Validate and update drone information
    }

    public function deleteDrone($id)
    {
        // Delete a drone
    }

    public function addCategory(Request $request)
    {
        // Validate and add a new category
    }

    public function updateCategory(Request $request, $id)
    {
        // Validate and update category information
    }

    public function deleteCategory($id)
    {
        // Delete a category
    }

    public function listUsers()
    {
        // List all users
    }

    public function listDrones()
    {
        // List all drones
    }

    public function listCategories()
    {
        // List all categories
    }

    public function listRentals()
    {
        // List all rentals
    }
}