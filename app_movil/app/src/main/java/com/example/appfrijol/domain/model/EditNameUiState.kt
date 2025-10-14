package com.example.appfrijol.domain.model


data class EditNameUiState(
    val isLoading: Boolean = false,
    val errorMessage: String? = null,
    val success: Boolean = false
)