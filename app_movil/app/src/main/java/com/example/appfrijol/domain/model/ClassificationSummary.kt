package com.example.appfrijol.domain.model

// Dominio
data class ClassificationSummary(
    val totalBeansClassified: Int,
    val aptPercentage: Float,
    val lastClassificationDate: String?,
    val beansByStatus: Map<String, Int>,
    val beansByColor: Map<String, Int>
)

