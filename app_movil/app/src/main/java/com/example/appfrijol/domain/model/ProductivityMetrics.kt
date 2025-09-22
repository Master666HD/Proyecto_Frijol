package com.example.appfrijol.domain.model

data class ProductivityMetrics(
    val total_beans: Int,
    val batches_last_week: Map<String, Int>
)