package com.example.appfrijol.domain.model

data class Batch(
     val start: String,
     val end: String,
     val total: Int,
     val seeds: List<Seed>
)