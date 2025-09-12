package com.example.appfrijol.domain.model

data class Lote(
    val inicio: String,
    val fin: String,
    val total: Int,
    val semillas: List<Semilla>
)