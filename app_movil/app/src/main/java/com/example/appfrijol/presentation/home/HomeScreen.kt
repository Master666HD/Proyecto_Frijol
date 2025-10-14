package com.example.appfrijol.presentation.home

import androidx.compose.animation.AnimatedVisibility
import androidx.compose.animation.animateContentSize
import androidx.compose.animation.core.Animatable
import androidx.compose.animation.core.tween
import androidx.compose.animation.fadeIn
import androidx.compose.animation.slideInVertically
import androidx.compose.foundation.Canvas
import androidx.compose.foundation.ExperimentalFoundationApi
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.ColumnScope
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.outlined.Assignment
import androidx.compose.material.icons.automirrored.outlined.TrendingUp
import androidx.compose.material.icons.filled.Warning
import androidx.compose.material.icons.outlined.BarChart
import androidx.compose.material.icons.outlined.Scale
import androidx.compose.material3.Card
import androidx.compose.material3.CardDefaults
import androidx.compose.material3.Divider
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.Icon
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.remember
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.alpha
import androidx.compose.ui.draw.clip
import androidx.compose.ui.geometry.CornerRadius
import androidx.compose.ui.geometry.Offset
import androidx.compose.ui.geometry.Size
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.StrokeCap
import androidx.compose.ui.graphics.nativeCanvas
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.platform.LocalDensity
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.hilt.navigation.compose.hiltViewModel
import com.example.appfrijol.domain.model.ProductivityMetrics
import kotlin.math.ceil
import kotlin.math.floor
import kotlin.math.roundToInt

@OptIn(ExperimentalMaterial3Api::class, ExperimentalFoundationApi::class)
@Composable
fun HomeScreen(
    userName: String,
    viewModel: HomeViewModel = hiltViewModel()
) {
    LaunchedEffect(Unit) { viewModel.loadData() }

    val lastBatchSummary = viewModel.lastBatchSummary
    val productivityMetrics = viewModel.productivityMetrics
    val error = viewModel.errorMessage
    val animatedVisibility = remember { Animatable(0f) }

    LaunchedEffect(Unit) {
        animatedVisibility.animateTo(1f, animationSpec = tween(800))
    }
    Box(
        modifier = Modifier
            .fillMaxSize()
            .background(
                brush = Brush.verticalGradient(
                    colors = listOf(
                        MaterialTheme.colorScheme.primary.copy(alpha = 0.15f),
                        MaterialTheme.colorScheme.background
                    )

                )
            )
    ) {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .verticalScroll(rememberScrollState()) // Scroll vertical
            .padding(10.dp)

    ) {
        // 👋 Bienvenida
        Card(
            modifier = Modifier
                .fillMaxWidth()
                .padding(horizontal = 16.dp)
                .alpha(animatedVisibility.value),
            shape = RoundedCornerShape(16.dp),
            colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surfaceVariant),
            elevation = CardDefaults.cardElevation(4.dp)
        ) {
            Column(modifier = Modifier.padding(20.dp)) {
                Text(
                    text = "¡Hola, $userName!",
                    style = MaterialTheme.typography.headlineSmall,
                    fontWeight = FontWeight.Bold,
                    color = MaterialTheme.colorScheme.onSurfaceVariant
                )
                Spacer(Modifier.height(4.dp))
                Text(
                    text = "Bienvenido a tu panel de control",
                    style = MaterialTheme.typography.bodyMedium,
                    color = MaterialTheme.colorScheme.onSurfaceVariant.copy(alpha = 0.7f)
                )
                Spacer(modifier = Modifier.height(8.dp))
                error?.let {
                    Card(
                        modifier = Modifier.fillMaxWidth(),
                        colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.errorContainer),
                        shape = RoundedCornerShape(12.dp)
                    ) {
                        Row(
                            modifier = Modifier.padding(12.dp),
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Icon(
                                imageVector = Icons.Default.Warning,
                                contentDescription = "Error",
                                tint = MaterialTheme.colorScheme.error
                            )
                            Spacer(modifier = Modifier.width(8.dp))
                            Text(
                                text = it,
                                color = MaterialTheme.colorScheme.error,
                                style = MaterialTheme.typography.bodyMedium
                            )
                        }
                    }
                    Spacer(modifier = Modifier.height(8.dp))
                }
            }
        }
        Spacer(modifier = Modifier.height(10.dp))
        // 📦 Resumen del último lote
        AnimatedVisibility(
            visible = animatedVisibility.value > 0.3f,
            enter = slideInVertically(initialOffsetY = { it / 2 }) + fadeIn(),
            modifier = Modifier
                .fillMaxWidth()
                .animateContentSize()
                .padding(horizontal = 1.dp)
        ) {
            AnalyticsCard(
                title = "Resumen del Último Lote",
                icon = Icons.AutoMirrored.Outlined.Assignment,
                content = {
                    lastBatchSummary?.let { summary ->
                        ImprovedBarChart(
                            title = "Semillas Clasificadas",
                            data = summary.by_status ?: emptyMap(),
                            barColor = MaterialTheme.colorScheme.tertiary
                        )
                        Spacer(Modifier.height(16.dp))
                        PieChartCard(
                            title = "Clasificación por Tamaño",
                            data = summary.by_size ?: emptyMap(),
                            colors = listOf(
                                Color(0xFF4CAF50),
                                Color(0xFF2196F3),
                                Color(0xFFFFC107),
                                Color(0xFFE91E63),
                                Color(0xFF9C27B0)
                            )
                        )
                        Spacer(Modifier.height(16.dp))
                        ImprovedBarChart(
                            title = "Clasificación por Color",
                            data = summary.by_color ?: emptyMap(),
                            barColor = MaterialTheme.colorScheme.tertiary
                        )
                        Spacer(Modifier.height(16.dp))
                        if (!summary.by_weight.isNullOrEmpty()) {
                            WeightRangeHorizontalBarChart(
                                title = "Distribución de Pesos por Semilla",
                                data = summary.by_weight,
                                barColor = MaterialTheme.colorScheme.secondary,
                                rangeSize = 0.2f
                            )
                            Spacer(Modifier.height(16.dp))
                        } else {
                            EmptyWeightChart("Distribución de Pesos por Semilla")
                            Spacer(Modifier.height(16.dp))
                        }
                        Spacer(Modifier.height(8.dp))
                        Text(
                            "Fecha: ${summary.date}",
                            fontSize = 12.sp,
                            color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.6f)
                        )
                    } ?: Box(
                        modifier = Modifier
                            .fillMaxWidth()
                            .height(200.dp),
                        contentAlignment = Alignment.Center
                    ) {
                        Text("No hay datos disponibles")
                    }
                }
            )
        }

        Spacer(Modifier.height(16.dp))

        // 📈 Métricas de productividad
        AnimatedVisibility(
            visible = animatedVisibility.value > 0.6f,
            enter = slideInVertically(initialOffsetY = { it / 2 }) + fadeIn(),
            modifier = Modifier
                .fillMaxWidth()
                .animateContentSize()
                .padding(horizontal = 1.dp)
        ) {
            AnalyticsCard(
                title = "Métricas de Productividad",
                icon = Icons.AutoMirrored.Outlined.TrendingUp,
                content = {
                    error?.let {
                        Card(
                            modifier = Modifier.fillMaxWidth(),
                            colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.errorContainer),
                            shape = RoundedCornerShape(12.dp)
                        ) {
                            Row(
                                modifier = Modifier.padding(12.dp),
                                verticalAlignment = Alignment.CenterVertically
                            ) {
                                Icon(
                                    imageVector = Icons.Default.Warning,
                                    contentDescription = "Error",
                                    tint = MaterialTheme.colorScheme.error
                                )
                                Spacer(modifier = Modifier.width(8.dp))
                                Text(
                                    text = it,
                                    color = MaterialTheme.colorScheme.error,
                                    style = MaterialTheme.typography.bodyMedium
                                )
                            }
                        }
                        Spacer(modifier = Modifier.height(8.dp))
                    }

                    val metrics = productivityMetrics

                    if (metrics == null ||
                        (metrics.total_beans == 0 && metrics.batches_last_week.values.all { it == 0 })
                    ) {
                        Box(
                            modifier = Modifier
                                .fillMaxWidth()
                                .height(150.dp),
                            contentAlignment = Alignment.Center
                        ) {
                            Text("No hay métricas disponibles")
                        }
                    } else {
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceEvenly
                        ) {
                            MetricCard(
                                title = "Total Clasificado",
                                value = "${metrics.total_beans}",
                                unit = "frijoles",
                                color = MaterialTheme.colorScheme.primary
                            )

                            MetricCard(
                                title = "Lotes Semana",
                                value = "${metrics.batches_last_week.values.sum()}",
                                unit = "lotes",
                                color = MaterialTheme.colorScheme.secondary
                            )
                        }

                        Spacer(Modifier.height(16.dp))

                        LineChartCard(
                            title = "Lotes de la Última Semana",
                            data = metrics.batches_last_week,
                            lineColor = MaterialTheme.colorScheme.primary
                        )
                    }
                }
            )
        }
    }
}
}



// Componente reutilizable para tarjetas de analytics
@Composable
fun AnalyticsCard(
    title: String,
    icon: ImageVector,
    content: @Composable ColumnScope.() -> Unit
) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 16.dp),
        shape = RoundedCornerShape(20.dp),
        elevation = CardDefaults.cardElevation(8.dp)
    ) {
        Column(
            modifier = Modifier.padding(20.dp)
        ) {
            Row(verticalAlignment = Alignment.CenterVertically) {
                Icon(
                    imageVector = icon,
                    contentDescription = null,
                    tint = MaterialTheme.colorScheme.primary,
                    modifier = Modifier.size(20.dp)
                )
                Spacer(Modifier.width(8.dp))
                Text(
                    text = title,
                    style = MaterialTheme.typography.titleMedium,
                    fontWeight = FontWeight.Bold,
                    color = MaterialTheme.colorScheme.onSurface
                )
            }

            Spacer(Modifier.height(16.dp))

            content()
        }
    }
}

@Composable
fun WeightRangeHorizontalBarChart(
    title: String,
    data: Map<String, Int>, // Peso (String) -> Cantidad (Int)
    barColor: Color,
    rangeSize: Float = 0.1f // Tamaño del rango de peso (ej. 0.1g)
) {
    // 1. Procesamiento y Agrupación de Datos en Rangos
    val allWeights = data.entries.flatMap { entry ->
        val weight = entry.key.toFloatOrNull() ?: return@flatMap emptyList()
        List(entry.value) { weight }
    }

    if (allWeights.isEmpty()) {
        // EmptyWeightChart(title) // Asumiendo que existe una función para manejar el estado vacío
        return
    }

    val minWeight = allWeights.minOrNull() ?: 0f
    val maxWeight = allWeights.maxOrNull() ?: 1f
    val totalSemillas = allWeights.size

    // Normalizar y agrupar los datos
    val rangeCounts = mutableMapOf<Float, Int>() // Inicio del rango -> Cantidad

    for (weight in allWeights) {
        val rangeKey = floor(weight / rangeSize) * rangeSize
        rangeCounts[rangeKey] = rangeCounts.getOrDefault(rangeKey, 0) + 1
    }

    // Convertir a una lista de (Etiqueta, Cantidad), ordenado por el inicio del rango
    val rangeData = rangeCounts.entries
        .sortedBy { it.key }
        .map { (start, count) ->
            val label = "%.1f-%.1f g".format(start, start + rangeSize)
            Pair(label, count) // Etiqueta (String), Cantidad (Int)
        }

    val maxCount = rangeData.maxOfOrNull { it.second } ?: 1
    val barCount = rangeData.size

    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 16.dp),
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surface),
        elevation = CardDefaults.cardElevation(4.dp)
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp)
        ) {
            Text(
                text = title,
                style = MaterialTheme.typography.titleMedium,
                fontWeight = FontWeight.SemiBold,
                color = MaterialTheme.colorScheme.onSurface
            )

            Spacer(Modifier.height(16.dp))

            // --- Gráfico de Barras Horizontal ---
            Box(
                modifier = Modifier
                    .fillMaxWidth()
                    .height((30 * barCount).coerceIn(150, 400).dp) // Altura dinámica basada en el # de rangos
            ) {
                Canvas(modifier = Modifier.fillMaxSize()) {
                    val paddingLeft = 100f // Más espacio para etiquetas Y (rangos)
                    val paddingRight = 30f
                    val paddingTop = 30f
                    val paddingBottom = 60f // Más espacio para la etiqueta X (cantidad)

                    val chartWidth = size.width - paddingLeft - paddingRight
                    val chartHeight = size.height - paddingTop - paddingBottom
                    val barSpacing = 10.dp.toPx() // Espacio entre barras horizontales
                    val barHeight = ((chartHeight - (barCount + 1) * barSpacing) / barCount).coerceAtLeast(10f)

                    // --- Ejes ---

                    // Eje X (CANTIDAD - horizontal)
                    drawLine(
                        color = Color.Gray.copy(alpha = 0.5f),
                        start = Offset(paddingLeft, size.height - paddingBottom),
                        end = Offset(size.width - paddingRight, size.height - paddingBottom),
                        strokeWidth = 2f
                    )

                    // Eje Y (RANGOS - vertical)
                    drawLine(
                        color = Color.Gray.copy(alpha = 0.5f),
                        start = Offset(paddingLeft, paddingTop),
                        end = Offset(paddingLeft, size.height - paddingBottom),
                        strokeWidth = 2f
                    )

                    // --- Líneas de Referencia y Etiquetas Eje X (Cantidad) ---
                    val xAxisLabelsCount = 5
                    for (i in 0 until xAxisLabelsCount) {
                        val countValue = (maxCount / (xAxisLabelsCount - 1).toFloat() * i).roundToInt()
                        val x = paddingLeft + chartWidth * (i / (xAxisLabelsCount - 1).toFloat())

                        // Línea de referencia vertical
                        if (i > 0) { // No dibujar la línea en el eje Y
                            drawLine(
                                color = Color.Gray.copy(alpha = 0.2f),
                                start = Offset(x, paddingTop),
                                end = Offset(x, size.height - paddingBottom),
                                strokeWidth = 1f
                            )
                        }

                        // Etiquetas del eje X (Cantidad)
                        drawContext.canvas.nativeCanvas.apply {
                            drawText(
                                countValue.toString(),
                                x,
                                size.height - paddingBottom + 20f,
                                android.graphics.Paint().apply {
                                    color = android.graphics.Color.GRAY
                                    textSize = 16f
                                    textAlign = android.graphics.Paint.Align.CENTER
                                }
                            )
                        }
                    }

                    // --- Dibujar Barras y Etiquetas de Rangos ---
                    rangeData.reversed().forEachIndexed { index, pair -> // Invertir para que el primer rango quede abajo
                        val (label, count) = pair

                        // Posición de la barra (de abajo hacia arriba)
                        val yOffset = size.height - paddingBottom - barSpacing - (index * (barHeight + barSpacing))

                        // Ancho de la barra (basado en la cantidad)
                        val barWidthFraction = chartWidth * (count.toFloat() / maxCount)

                        // Barra horizontal
                        drawRect(
                            color = barColor,
                            topLeft = Offset(paddingLeft, yOffset - barHeight), // x=paddingLeft, y=desde el fondo
                            size = Size(barWidthFraction, barHeight)
                        )

                        // Etiqueta del Eje Y (Rango de Peso)
                        drawContext.canvas.nativeCanvas.apply {
                            drawText(
                                label,
                                paddingLeft - 5f,
                                yOffset - barHeight / 2f + 5f, // Centrar verticalmente
                                android.graphics.Paint().apply {
                                    color = android.graphics.Color.GRAY
                                    textSize = 16f
                                    textAlign = android.graphics.Paint.Align.RIGHT
                                }
                            )
                        }

                        // Etiqueta de valor de la barra (Cantidad) - Colocar a la derecha de la barra
                        drawContext.canvas.nativeCanvas.apply {
                            drawText(
                                count.toString(),
                                paddingLeft + barWidthFraction + 10f, // 10f de margen a la derecha de la barra
                                yOffset - barHeight / 2f + 5f, // Centrar verticalmente
                                android.graphics.Paint().apply {
                                    color = android.graphics.Color.BLACK
                                    textSize = 18f
                                    textAlign = android.graphics.Paint.Align.LEFT
                                    typeface = android.graphics.Typeface.DEFAULT_BOLD
                                }
                            )
                        }
                    }

                    // Etiquetas de los ejes
                    drawContext.canvas.nativeCanvas.apply {
                        drawText(
                            "Rango de Pesos (g)",
                            paddingLeft - 85f,
                            paddingTop - 10f,
                            android.graphics.Paint().apply {
                                color = android.graphics.Color.GRAY
                                textSize = 20f
                                textAlign = android.graphics.Paint.Align.LEFT
                            }
                        )
                    }

                    drawContext.canvas.nativeCanvas.apply {
                        drawText(
                            "Cantidad de Semillas",
                            size.width / 2,
                            size.height - 20f,
                            android.graphics.Paint().apply {
                                color = android.graphics.Color.GRAY
                                textSize = 20f
                                textAlign = android.graphics.Paint.Align.CENTER
                            }
                        )
                    }
                }
            }


            Spacer(Modifier.height(16.dp))
            val promedio = allWeights.average()
            val pesoMaximo = maxWeight
            val pesoMinimo = minWeight

            // Estadísticas Generales
            Column {
                Row(
                    modifier = Modifier.fillMaxWidth(),
                    horizontalArrangement = Arrangement.SpaceBetween
                ) {
                    Text(
                        text = "Total Semillas: $totalSemillas",
                        style = MaterialTheme.typography.bodyMedium,
                        fontWeight = FontWeight.Bold,
                        color = MaterialTheme.colorScheme.onSurface
                    )
                    Text(
                        text = "Promedio: ${"%.2f".format(promedio)} g",
                        style = MaterialTheme.typography.bodyMedium,
                        fontWeight = FontWeight.Bold,
                        color = MaterialTheme.colorScheme.onSurface
                    )
                }
                Spacer(Modifier.height(4.dp))
                Row(
                    modifier = Modifier.fillMaxWidth(),
                    horizontalArrangement = Arrangement.SpaceBetween
                ) {
                    Text(
                        text = "Rango Min/Max: ${"%.2f".format(pesoMinimo)} - ${"%.2f".format(pesoMaximo)} g",
                        style = MaterialTheme.typography.bodySmall,
                        color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.7f)
                    )
                    Text(
                        text = "Tamaño del Intervalo: ${"%.1f".format(rangeSize)} g",
                        style = MaterialTheme.typography.bodySmall,
                        color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.7f)
                    )
                }
            }

            Spacer(Modifier.height(16.dp))
            Divider()
            Spacer(Modifier.height(16.dp))

            // Distribución por Porcentaje
            Text(
                text = "Distribución por Rangos",
                style = MaterialTheme.typography.titleSmall,
                fontWeight = FontWeight.SemiBold,
                color = MaterialTheme.colorScheme.onSurface
            )
            Spacer(Modifier.height(8.dp))
            Column(modifier = Modifier.fillMaxWidth()) {
                rangeData.forEach { (label, count) ->
                    val percentage = (count.toFloat() / totalSemillas) * 100
                    Row(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(vertical = 4.dp),
                        horizontalArrangement = Arrangement.SpaceBetween
                    ) {
                        Text(
                            text = "$label:",
                            style = MaterialTheme.typography.bodySmall,
                            color = MaterialTheme.colorScheme.onSurface
                        )
                        Text(
                            text = "$count semillas (${"%.1f".format(percentage)}%)",
                            style = MaterialTheme.typography.bodySmall,
                            fontWeight = FontWeight.Medium,
                            color = MaterialTheme.colorScheme.primary
                        )
                    }
                }
            }
        }
    }
}

@Composable
fun EmptyWeightChart(title: String) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 16.dp),
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surface),
        elevation = CardDefaults.cardElevation(4.dp)
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Text(
                text = title,
                style = MaterialTheme.typography.titleMedium,
                fontWeight = FontWeight.SemiBold,
                color = MaterialTheme.colorScheme.onSurface
            )
            Spacer(Modifier.height(16.dp))
            Text(
                text = "No hay datos de peso disponibles",
                style = MaterialTheme.typography.bodyMedium,
                color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.5f)
            )
        }
    }
}
@Composable
fun ImprovedBarChart(
    title: String,
    data: Map<String, Int>,
    barColor: Color
) {
    val maxValue = (data.values.maxOrNull() ?: 1).toFloat()
    val sortedData = data.toList().sortedByDescending { it.second }

    Column {
        Text(
            text = title,
            style = MaterialTheme.typography.bodyLarge,
            fontWeight = FontWeight.Medium,
            color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.8f)
        )

        Spacer(Modifier.height(12.dp))

        // Barras con etiquetas de valor
        sortedData.forEach { (label, value) ->
            Row(
                modifier = Modifier
                    .fillMaxWidth()
                    .height(28.dp)
                    .padding(vertical = 2.dp),
                verticalAlignment = Alignment.CenterVertically
            ) {
                Text(
                    text = label,
                    modifier = Modifier.width(60.dp),
                    fontSize = 12.sp,
                    fontWeight = FontWeight.Medium
                )

                Spacer(Modifier.width(8.dp))

                // Barra con animación
                val animatedProgress = remember { Animatable(0f) }
                LaunchedEffect(Unit) {
                    animatedProgress.animateTo(value.toFloat() / maxValue)
                }

                Box(
                    modifier = Modifier
                        .weight(1f)
                        .height(20.dp)
                        .clip(RoundedCornerShape(4.dp))
                        .background(MaterialTheme.colorScheme.surfaceVariant)
                ) {
                    Box(
                        modifier = Modifier
                            .fillMaxWidth(animatedProgress.value)
                            .fillMaxHeight()
                            .clip(RoundedCornerShape(4.dp))
                            .background(barColor)
                    )
                }

                Spacer(Modifier.width(8.dp))

                Text(
                    text = value.toString(),
                    fontSize = 12.sp,
                    fontWeight = FontWeight.Bold
                )
            }
        }
    }
}

// Gráfica circular
@Composable
fun PieChartCard(
    title: String,
    data: Map<String, Int>,
    colors: List<Color>
) {
    val total = data.values.sum().toFloat()
    var startAngle = 0f

    Column {
        Text(
            text = title,
            style = MaterialTheme.typography.bodyLarge,
            fontWeight = FontWeight.Medium,
            color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.8f)
        )

        Spacer(Modifier.height(12.dp))

        Row(
            modifier = Modifier.fillMaxWidth(),
            verticalAlignment = Alignment.CenterVertically
        ) {
            // Gráfica circular
            Box(
                modifier = Modifier.size(120.dp),
                contentAlignment = Alignment.Center
            ) {
                Canvas(modifier = Modifier.size(120.dp)) {
                    data.values.forEachIndexed { index, value ->
                        val sweepAngle = (value / total) * 360f
                        drawArc(
                            color = colors[index % colors.size],
                            startAngle = startAngle,
                            sweepAngle = sweepAngle,
                            useCenter = true
                        )
                        startAngle += sweepAngle
                    }
                }
            }

            Spacer(Modifier.width(16.dp))

            // Leyenda
            Column(
                modifier = Modifier.weight(1f)
            ) {
                data.keys.forEachIndexed { index, label ->
                    Row(
                        modifier = Modifier.padding(vertical = 4.dp),
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Box(
                            modifier = Modifier
                                .size(12.dp)
                                .background(colors[index % colors.size], CircleShape)
                        )
                        Spacer(Modifier.width(8.dp))
                        Text(
                            text = label,
                            fontSize = 12.sp,
                            modifier = Modifier.weight(1f)
                        )
                        Text(
                            text = "${((data.values.elementAt(index) / total) * 100).toInt()}%",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold
                        )
                    }
                }
            }
        }
    }
}

// Gráfica de líneas
@OptIn(ExperimentalMaterial3Api::class)

@Composable
fun LineChartCard(
    title: String,
    data: Map<String, Int>,       // clave: fecha o categoría
    lineColor: Color
) {
    // 1. Preparación de Datos
    val sortedData = data.toList().sortedBy { it.first }
    val categories = sortedData.map { entry ->
        val parts = entry.first.split("-")
        if (parts.size == 3) "${parts[2]}/${parts[1]}" else entry.first
    }
    // Los pesos son enteros, pero el Canvas trabaja con Float para el cálculo
    val weights = sortedData.map { it.second.toFloat() }

    if (weights.isEmpty() || categories.isEmpty()) {
        EmptyChartPlaceholder(title = title)
        return
    }

    // 2. Definición de la Escala (Ajustada para Enteros)
    val maxWeight = (weights.maxOrNull() ?: 1f).toInt()
    val minWeight = (weights.minOrNull() ?: 0f).toInt()

    // Escala con "relleno" para que la línea no toque los bordes superior/inferior
    // Mínimo y Máximo escalados al entero más cercano divisible por un factor (ej. 5 o 10)
    val factor = 5 // Factor de ajuste de la escala
    val minScaledInt = (floor((minWeight - 1).toFloat() / factor) * factor).toInt().coerceAtLeast(0)
    val maxScaledInt = (ceil((maxWeight + 1).toFloat() / factor) * factor).toInt()

    val effectiveMinWeightFloat = minScaledInt.toFloat()
    val paddedWeightRangeFloat = (maxScaledInt - minScaledInt).toFloat().coerceAtLeast(5f)

    // Determinamos los valores de las líneas de la cuadrícula
    val gridValuesCount = 4 // 5 puntos (0, 1, 2, 3, 4) para 4 intervalos
    val step = (paddedWeightRangeFloat / gridValuesCount).roundToInt().coerceAtLeast(1)
    val gridValues = List(gridValuesCount + 1) { minScaledInt + it * step }
        .filter { it <= maxScaledInt + step } // Asegurar que no nos pasemos mucho
        .distinct() // Evitar duplicados si el paso es grande

    if (gridValues.size < 2) {
        // Recalculamos si el paso hizo que la escala sea muy pequeña
        // Esto previene errores de división si la escala es 0
        // ... (Se podría añadir una lógica de respaldo, pero simplificaremos asumiendo datos variados)
        return
    }

    // Recalcular el rango efectivo basado en los puntos reales del grid
    val finalMin = gridValues.first().toFloat()
    val finalMax = gridValues.last().toFloat()
    val finalRange = finalMax - finalMin

    // --- Estructura del Componente ---
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(8.dp),
        colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surface),
        elevation = CardDefaults.cardElevation(4.dp)
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp)
        ) {
            Text(
                text = title,
                style = MaterialTheme.typography.titleMedium,
                fontWeight = FontWeight.SemiBold,
                color = MaterialTheme.colorScheme.onSurface
            )

            Spacer(Modifier.height(16.dp))

            Box(
                modifier = Modifier
                    .fillMaxWidth()
                    .height(250.dp)
            ) {
                Canvas(modifier = Modifier.fillMaxSize()) {
                    val paddingLeft = 60f
                    val paddingRight = 30f
                    val paddingTop = 30f
                    val paddingBottom = 50f

                    val chartWidth = size.width - paddingLeft - paddingRight
                    val chartHeight = size.height - paddingTop - paddingBottom

                    // Eje Y
                    drawLine(
                        color = Color.Gray.copy(alpha = 0.5f),
                        start = Offset(paddingLeft, paddingTop),
                        end = Offset(paddingLeft, size.height - paddingBottom),
                        strokeWidth = 2f
                    )

                    // Eje X
                    drawLine(
                        color = Color.Gray.copy(alpha = 0.5f),
                        start = Offset(paddingLeft, size.height - paddingBottom),
                        end = Offset(size.width - paddingRight, size.height - paddingBottom),
                        strokeWidth = 2f
                    )

                    // ✅ MODIFICACIÓN CLAVE: Líneas horizontales de referencia (grid) - SOLO ENTEROS
                    gridValues.forEachIndexed { index, weightValueInt ->

                        // Normalizar la posición del valor del grid
                        val normalized = (weightValueInt.toFloat() - finalMin) / finalRange
                        val y = paddingTop + chartHeight * (1 - normalized)

                        // Dibujar línea de referencia
                        drawLine(
                            color = Color.Gray.copy(alpha = 0.2f),
                            start = Offset(paddingLeft, y),
                            end = Offset(size.width - paddingRight, y),
                            strokeWidth = 1f
                        )

                        // Dibujar etiqueta (solo entero)
                        drawContext.canvas.nativeCanvas.apply {
                            drawText(
                                weightValueInt.toString(), // Siempre entero
                                paddingLeft - 10f,
                                y + 5f,
                                android.graphics.Paint().apply {
                                    color = android.graphics.Color.GRAY
                                    textSize = 24f
                                    textAlign = android.graphics.Paint.Align.RIGHT
                                }
                            )
                        }
                    }

                    // 3. Cálculo de Puntos (Usando la Escala Entera)
                    val points = weights.mapIndexed { index, weight ->
                        // Normalizar el peso actual dentro de la escala final (finalMin/finalRange)
                        val normalized = (weight - finalMin) / finalRange

                        val clampedNormalized = normalized.coerceIn(0f, 1f)

                        val x = paddingLeft + (index.toFloat() / (weights.size - 1)) * chartWidth
                        val y = paddingTop + chartHeight * (1 - clampedNormalized)
                        Offset(x, y)
                    }

                    // Dibujar línea
                    if (points.size > 1) {
                        for (i in 0 until points.size - 1) {
                            drawLine(
                                color = lineColor,
                                start = points[i],
                                end = points[i + 1],
                                strokeWidth = 4f,
                                cap = StrokeCap.Round
                            )
                        }
                    }

                    // Dibujar puntos
                    points.forEach { point ->
                        drawCircle(color = lineColor, radius = 6f, center = point)
                        drawCircle(color = Color.White, radius = 2f, center = point)
                    }

                    // Etiquetas X (días)
                    // ... (El resto del código para las etiquetas X y los títulos de los ejes es el mismo)
                    categories.forEachIndexed { index, category ->
                        val x = paddingLeft + (index.toFloat() / (categories.size - 1)) * chartWidth

                        drawContext.canvas.nativeCanvas.apply {
                            save()
                            translate(x, size.height - paddingBottom + 20f)
                            rotate(-45f)
                            drawText(
                                category,
                                0f,
                                0f,
                                android.graphics.Paint().apply {
                                    color = android.graphics.Color.GRAY
                                    textSize = 20f
                                    textAlign = android.graphics.Paint.Align.CENTER
                                }
                            )
                            restore()
                        }
                    }

                    // Etiqueta Y
                    drawContext.canvas.nativeCanvas.apply {
                        drawText(
                            "Lotes",
                            paddingLeft - 50f,
                            paddingTop - 10f,
                            android.graphics.Paint().apply {
                                color = android.graphics.Color.GRAY
                                textSize = 24f
                                textAlign = android.graphics.Paint.Align.RIGHT
                            }
                        )
                    }

                    // Etiqueta X
                    drawContext.canvas.nativeCanvas.apply {
                        drawText(
                            "Días",
                            size.width / 2,
                            size.height - 10f,
                            android.graphics.Paint().apply {
                                color = android.graphics.Color.GRAY
                                textSize = 24f
                                textAlign = android.graphics.Paint.Align.CENTER
                            }
                        )
                    }
                }
            }
        }
    }
}


@Composable
fun EmptyChartPlaceholder(title: String) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(8.dp),
        colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surface.copy(alpha = 0.5f))
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(24.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Icon(
                imageVector = Icons.Outlined.BarChart,
                contentDescription = "Sin datos",
                modifier = Modifier.size(32.dp),
                tint = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.5f)
            )
            Spacer(Modifier.height(8.dp))
            Text(
                text = title,
                style = MaterialTheme.typography.titleMedium,
                fontWeight = FontWeight.SemiBold
            )
            Spacer(Modifier.height(4.dp))
            Text(
                text = "No hay datos de peso disponibles",
                style = MaterialTheme.typography.bodyMedium,
                color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.6f)
            )
        }
    }
}

// Tarjeta de métrica
@Composable
fun MetricCard(
    title: String,
    value: String,
    unit: String,
    color: Color
) {
    Card(
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(
            containerColor = color.copy(alpha = 0.1f)
        ),
        elevation = CardDefaults.cardElevation(2.dp)
    ) {
        Column(
            modifier = Modifier.padding(12.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Text(
                text = value,
                style = MaterialTheme.typography.titleLarge,
                fontWeight = FontWeight.Bold,
                color = color
            )
            Text(
                text = title,
                style = MaterialTheme.typography.bodySmall,
                color = color
            )
            Text(
                text = unit,
                style = MaterialTheme.typography.bodySmall,
                color = color.copy(alpha = 0.7f)
            )
        }
    }
}