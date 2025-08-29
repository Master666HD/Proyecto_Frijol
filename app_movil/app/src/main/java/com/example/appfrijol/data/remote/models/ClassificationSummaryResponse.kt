package com.example.appfrijol.data.remote.models

import com.example.appfrijol.domain.model.ClassificationSummary
import com.google.gson.annotations.SerializedName

// Remote (respuesta JSON de tu API)
data class ClassificationSummaryResponse(
    @SerializedName("totalFrijolesClasificados") val totalFrijolesClasificados: Int,
    @SerializedName("porcentajeAptos") val porcentajeAptos: Float,
    @SerializedName("ultimaClasificacionFecha") val ultimaClasificacionFecha: String?,
    @SerializedName("frijolesPorEstado") val frijolesPorEstado: Map<String, Int>,
    @SerializedName("frijolesPorColor") val frijolesPorColor: Map<String, Int>
)


// Mapper Remote → Domain
fun ClassificationSummaryResponse.toDomain() = ClassificationSummary(
    totalBeansClassified = totalFrijolesClasificados,
    aptPercentage = porcentajeAptos,
    lastClassificationDate = ultimaClasificacionFecha,
    beansByStatus = frijolesPorEstado,
    beansByColor = frijolesPorColor
)

