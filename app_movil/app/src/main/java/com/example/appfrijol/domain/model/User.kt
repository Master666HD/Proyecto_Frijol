package com.example.appfrijol.domain.model

data class User(
    val firstName: String,
    val lastName: String,
    val phoneNumber: String,
    val email: String,
    val userName: String,
    val password: String,
    val role: String
)
