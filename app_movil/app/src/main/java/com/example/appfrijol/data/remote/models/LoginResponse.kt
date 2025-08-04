package com.example.appfrijol.data.remote.models

import com.example.appfrijol.domain.model.UserInfo

data class LoginResponse(
    val message: String,
    val user: UserInfo,
    val token: String
)

