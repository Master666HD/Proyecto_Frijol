package com.example.appfrijol.domain.model

data class LastBatchSummary(
    val by_color: Map<String, Int>,
    val by_size: Map<String, Int>,
    val by_weight: Map<String, Int>,
    val by_status: Map<String, Int>,
    val date: String
)