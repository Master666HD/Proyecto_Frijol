package com.example.appfrijol.presentation.register

data class RegisterUiState(
    val firstName: String = "",
    val lastName: String = "",
    val phoneNumber: String = "",
    val email: String = "",
    val userName: String = "",
    val password: String = "",
    val isLoading: Boolean = false,
    val errors: Map<String, String> = emptyMap(),
    val message: String = ""
)


