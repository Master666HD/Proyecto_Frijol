package com.example.appfrijol.data.remote.models

data class ProductivityMetricsResponse(
    val total_beans: Int,
    val batches_last_week: Map<String, Int>
)