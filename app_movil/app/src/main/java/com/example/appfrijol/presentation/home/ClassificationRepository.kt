package com.example.appfrijol.presentation.home

import com.example.appfrijol.data.remote.api.ApiService
import com.example.appfrijol.data.remote.models.toDomain
import com.example.appfrijol.domain.model.ClassificationSummary
import javax.inject.Inject

class ClassificationRepository @Inject constructor(
    private val api: ApiService
) {
    suspend fun getClassificationSummary(userId: String): ClassificationSummary {
        val response = api.getClassificationSummary(userId)
        return response.toDomain() // Convierte JSON → Domain
    }
}


