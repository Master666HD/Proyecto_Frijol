package com.example.appfrijol.data.remote.dto

data class LastBatchSummaryDto(
    val by_color: Map<String, Int>,
    val by_size: Map<String, Int>,
    val by_weight: Map<String, Int>,
    val by_status: Map<String, Int>,
    val date: String
)