package com.example.appfrijol.data.remote.dto

data class BatchDto(
    val start: String,
    val end: String,
    val total: Int,
    val seeds: List<SeedDto>
)