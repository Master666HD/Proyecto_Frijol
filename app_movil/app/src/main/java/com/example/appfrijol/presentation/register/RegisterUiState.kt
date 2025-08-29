package com.example.appfrijol.presentation.register

data class RegisterUiState(
    val isLoading: Boolean = false,
    val errors: Map<String, String> = emptyMap(),
    val message: String = ""
)

