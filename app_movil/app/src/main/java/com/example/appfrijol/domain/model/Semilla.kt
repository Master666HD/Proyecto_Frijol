package com.example.appfrijol.domain.model

data class Semilla(
    val id: Int,
    val color: String,
    val tamano: String,
    val peso: Double,
    val estado: String,
    val fechaRegistro: String,
    val idUsuario: Int,
    val idPrototipo: Int
)