package com.example.appfrijol.data.remote.dto

data class ProductivityMetricsDto(
    val total_beans: Int,
    val batches_last_week: Map<String, Int>
)