package com.example.appfrijol.data.remote.models

data class LastBatchSummaryResponse(
    val by_color: Map<String, Int>,
    val by_size: Map<String, Int>,
    val by_weight: Map<String, Int>,
    val date: String
)