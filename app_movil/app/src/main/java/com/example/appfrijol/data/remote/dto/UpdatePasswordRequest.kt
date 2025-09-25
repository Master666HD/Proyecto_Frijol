package com.example.appfrijol.data.remote.dto

data class UpdatePasswordRequest(
    val currentPassword: String,
    val newPassword: String,
    val newPassword_confirmation: String
)