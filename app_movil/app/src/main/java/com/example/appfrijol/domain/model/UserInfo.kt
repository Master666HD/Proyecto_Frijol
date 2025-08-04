package com.example.appfrijol.domain.model

data class UserInfo(
    val id: Int,
    val firstName: String?, // Coincide con el backend
    val lastName: String?,  // Coincide con el backend
    val email: String?,     // Coincide con el backend
    val userName: String?,  // Coincide con el backend
    val role: String,      // Agregado para que coincida con el backend
    val token: String? = null
)
