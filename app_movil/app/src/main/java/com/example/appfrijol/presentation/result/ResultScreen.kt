package com.example.appfrijol.presentation.result

import android.util.Log
import android.widget.Toast
import androidx.compose.animation.core.FastOutSlowInEasing
import androidx.compose.animation.core.animateFloatAsState
import androidx.compose.animation.core.tween
import androidx.compose.foundation.BorderStroke
import androidx.compose.foundation.Canvas
import androidx.compose.foundation.ExperimentalFoundationApi
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
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
import androidx.compose.foundation.layout.heightIn
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.itemsIndexed
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Compare
import androidx.compose.material.icons.filled.Download
import androidx.compose.material.icons.filled.PictureAsPdf
import androidx.compose.material.icons.filled.TableChart
import androidx.compose.material.icons.filled.Warning
import androidx.compose.material.icons.outlined.Inventory2
import androidx.compose.material3.BottomSheetDefaults
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.Card
import androidx.compose.material3.CardColors
import androidx.compose.material3.CardDefaults
import androidx.compose.material3.CardElevation
import androidx.compose.material3.Checkbox
import androidx.compose.material3.CheckboxDefaults
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.Icon
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.ModalBottomSheet
import androidx.compose.material3.OutlinedButton
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Tab
import androidx.compose.material3.TabRow
import androidx.compose.material3.Text
import androidx.compose.material3.rememberModalBottomSheetState
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.rememberCoroutineScope
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.geometry.CornerRadius
import androidx.compose.ui.geometry.Offset
import androidx.compose.ui.geometry.Size
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.PaintingStyle.Companion.Stroke
import androidx.compose.ui.graphics.Shape
import androidx.compose.ui.graphics.drawscope.Stroke
import androidx.compose.ui.graphics.nativeCanvas
import androidx.compose.ui.graphics.toArgb
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.Dp
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.hilt.navigation.compose.hiltViewModel
import com.example.appfrijol.domain.model.Seed
import com.example.appfrijol.presentation.session.SessionViewModel
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import java.util.Locale
import kotlin.math.cos
import kotlin.math.roundToInt
import kotlin.math.sin

@OptIn(ExperimentalMaterial3Api::class, ExperimentalFoundationApi::class)
@Composable
fun ResultScreen(
    sessionViewModel: SessionViewModel = hiltViewModel(),
    seedViewModel: SeedViewModel = hiltViewModel()
) {
    val context = LocalContext.current
    val userName by sessionViewModel.userName.collectAsState()
    val lotes by seedViewModel.history.collectAsState()
    val error by seedViewModel.error.collectAsState()

    val sheetState = rememberModalBottomSheetState(skipPartiallyExpanded = false)
    val coroutineScope = rememberCoroutineScope()

    var semillasEnLote by remember { mutableStateOf<List<Seed>>(emptyList()) }
    var tituloLote by remember { mutableStateOf("") }
    var selectedTab by remember { mutableStateOf(0) }

    LaunchedEffect(Unit) { seedViewModel.loadHistory() }

    // Colores personalizados
    val primaryColor = MaterialTheme.colorScheme.primary
    val surfaceColor = MaterialTheme.colorScheme.surface
    val onSurfaceColor = MaterialTheme.colorScheme.onSurface


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
                    .verticalScroll(rememberScrollState())
                    .padding(16.dp)
            ) {
                // Encabezado de bienvenida
                Card(
                    modifier = Modifier.fillMaxWidth(),
                    colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surface),
                    elevation = CardDefaults.cardElevation(4.dp),
                    shape = RoundedCornerShape(12.dp)
                ) {
                    Column(modifier = Modifier.padding(16.dp)) {
                        Text(
                            text = "Hola, $userName!",
                            style = MaterialTheme.typography.headlineSmall,
                            fontWeight = FontWeight.Bold,
                            color = primaryColor
                        )
                        Spacer(modifier = Modifier.height(4.dp))
                        Text(
                            text = "Revisa el historial de lotes procesados",
                            style = MaterialTheme.typography.bodyMedium,
                            color = onSurfaceColor.copy(alpha = 0.7f)
                        )
                    }
                }

                Spacer(modifier = Modifier.height(16.dp))

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

                // Lista de lotes
                Text(
                    text = "Tus Lotes Registrados",
                    style = MaterialTheme.typography.titleMedium,
                    fontWeight = FontWeight.SemiBold,
                    modifier = Modifier.padding(vertical = 8.dp)
                )

                if (lotes.isEmpty()) {
                    Column(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(32.dp),
                        horizontalAlignment = Alignment.CenterHorizontally
                    ) {
                        Icon(
                            imageVector = Icons.Outlined.Inventory2,
                            contentDescription = "Sin lotes",
                            tint = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.5f),
                            modifier = Modifier.size(64.dp)
                        )
                        Spacer(modifier = Modifier.height(16.dp))
                        Text(
                            text = "No hay lotes registrados",
                            style = MaterialTheme.typography.bodyLarge,
                            color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.7f)
                        )
                    }
                } else {
                    Column(
                        modifier = Modifier.fillMaxWidth(),
                        verticalArrangement = Arrangement.spacedBy(12.dp)
                    ) {
                        lotes.forEachIndexed { index, lote ->
                            AnimatedCard(
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .clickable {
                                        semillasEnLote = lote.seeds
                                        tituloLote =
                                            "Lote ${index + 1}: ${lote.start} - ${lote.end}"
                                        coroutineScope.launch { sheetState.show() }
                                    }
                                    ,
                                elevation = CardDefaults.cardElevation(2.dp),
                                colors = CardDefaults.cardColors(containerColor = surfaceColor)
                            ) {
                                Row(
                                    modifier = Modifier
                                        .fillMaxWidth()
                                        .padding(16.dp),
                                    horizontalArrangement = Arrangement.SpaceBetween,
                                    verticalAlignment = Alignment.CenterVertically
                                ) {
                                    Column(modifier = Modifier.weight(1f)) {
                                        Text(
                                            text = "Lote ${index + 1}",
                                            style = MaterialTheme.typography.titleSmall,
                                            fontWeight = FontWeight.Bold
                                        )
                                        Spacer(modifier = Modifier.height(4.dp))
                                        Text(
                                            text = "${lote.start} - ${lote.end}",
                                            style = MaterialTheme.typography.bodySmall,
                                            color = onSurfaceColor.copy(alpha = 0.7f)
                                        )
                                        Spacer(modifier = Modifier.height(4.dp))
                                        Text(
                                            text = "${lote.total} semillas",
                                            style = MaterialTheme.typography.bodyMedium,
                                            color = primaryColor
                                        )
                                    }

                                }
                            }
                        }
                        val token = sessionViewModel.token.collectAsState().value

                        Spacer(modifier = Modifier.height(16.dp))
                        ExportPdfButton(
                            seedViewModel = seedViewModel,
                            token = token.toString()
                        )
                    }
                }

                Spacer(modifier = Modifier.height(16.dp))


                Spacer(modifier = Modifier.height(24.dp))
            }

            // --- Modal Bottom Sheet Mejorado ---
            if (sheetState.isVisible) {
                ModalBottomSheet(
                    onDismissRequest = { coroutineScope.launch { sheetState.hide() } },
                    sheetState = sheetState,
                    containerColor = MaterialTheme.colorScheme.surface,
                    tonalElevation = 16.dp,
                    dragHandle = { BottomSheetDefaults.DragHandle() },
                    shape = RoundedCornerShape(topStart = 16.dp, topEnd = 16.dp)
                ) {
                    Column(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(horizontal = 16.dp, vertical = 8.dp)
                            .verticalScroll(rememberScrollState())
                    ) {
                        Text(
                            text = tituloLote,
                            style = MaterialTheme.typography.titleLarge,
                            fontWeight = FontWeight.Bold,
                            modifier = Modifier.padding(bottom = 8.dp)
                        )

                        // Tabs para diferentes visualizaciones
                        TabRow(
                            selectedTabIndex = selectedTab,
                            containerColor = MaterialTheme.colorScheme.surface,
                            contentColor = primaryColor,
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            Tab(
                                selected = selectedTab == 0,
                                onClick = { selectedTab = 0 },
                                text = { Text("Gráficos") }
                            )
                            Tab(
                                selected = selectedTab == 1,
                                onClick = { selectedTab = 1 },
                                text = { Text("Datos") }
                            )
                        }

                        Spacer(modifier = Modifier.height(16.dp))

                        when (selectedTab) {
                            0 -> GraficasTab(semillasEnLote)
                            1 -> DatosTab(semillasEnLote)
                        }

                        Spacer(modifier = Modifier.height(16.dp))

                        // Botones de exportación
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceEvenly
                        ) {
                            val fechaInicio = semillasEnLote.firstOrNull()?.registration_date?.replace(":", "-") ?: "inicio"
                            val fechaFin = semillasEnLote.lastOrNull()?.registration_date?.replace(":", "-") ?: "fin"

                            OutlinedButton(
                                onClick = {
                                    coroutineScope.launch {
                                        val result = seedViewModel.exportBatch(semillasEnLote, "csv")
                                        result.onSuccess { data ->
                                            seedViewModel.saveAndOpenFile(
                                                context,
                                                data,
                                                "lote_${fechaInicio}_a_${fechaFin}.csv",
                                                "csv"
                                            )
                                        }.onFailure { e ->
                                            Toast.makeText(context, "Error: ${e.message}", Toast.LENGTH_SHORT).show()
                                        }
                                    }
                                },
                                shape = RoundedCornerShape(12.dp),
                                border = BorderStroke(1.dp, primaryColor),
                                colors = ButtonDefaults.outlinedButtonColors(contentColor = primaryColor)
                            ) {
                                Icon(Icons.Default.TableChart, contentDescription = "CSV")
                                Spacer(Modifier.width(8.dp))
                                Text("CSV")
                            }

                            Button(
                                onClick = {
                                    coroutineScope.launch {
                                        val result = seedViewModel.exportBatch(semillasEnLote, "pdf")
                                        result.onSuccess { data ->
                                            seedViewModel.saveAndOpenFile(
                                                context,
                                                data,
                                                "lote_${fechaInicio}_a_${fechaFin}.pdf",
                                                "pdf"
                                            )
                                        }.onFailure { e ->
                                            Toast.makeText(context, "Error: ${e.message}", Toast.LENGTH_SHORT).show()
                                        }
                                    }
                                },
                                shape = RoundedCornerShape(12.dp),
                                colors = ButtonDefaults.buttonColors(containerColor = primaryColor)
                            ) {
                                Icon(Icons.Default.PictureAsPdf, contentDescription = "PDF")
                                Spacer(Modifier.width(8.dp))
                                Text("PDF")
                            }
                        }

                        Spacer(modifier = Modifier.height(24.dp))
                    }
                }
            }
        }
}
@Composable
fun ExportPdfButton(
    seedViewModel: SeedViewModel,
    token: String, // Tu token de autenticación
    modifier: Modifier = Modifier
) {
    val context = LocalContext.current

    Button(
        onClick = {
            seedViewModel.downloadAllBatchesPdf(context, token)
        },
        modifier = modifier
            .fillMaxWidth()
            .padding(16.dp),
        shape = RoundedCornerShape(12.dp)
    ) {
        Icon(
            imageVector = Icons.Default.Download,
            contentDescription = "Descargar PDF",
            modifier = Modifier.size(24.dp)
        )
        Spacer(modifier = Modifier.width(8.dp))
        Text(text = "Exportar PDF de Lotes")
    }
}

@Composable
fun GraficasTab(semillasEnLote: List<Seed>) {
    val context = LocalContext.current

    if (semillasEnLote.isEmpty()) {
        Box(
            modifier = Modifier
                .fillMaxWidth()
                .height(200.dp),
            contentAlignment = Alignment.Center
        ) {
            Text("No hay datos para mostrar", color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.5f))
        }
        return
    }


    val datosPorEstado: Map<String, Int> = semillasEnLote
        .groupBy { it.status.lowercase() } // Agrupa en "apto" y "no apto"
        .mapValues { it.value.size }       // Cuenta cuántos hay de cada grupo

    val datosPorColor = semillasEnLote.groupBy { it.color.lowercase() }
        .mapValues { it.value.size }
    val datosPorTamaño = semillasEnLote.groupBy { it.size.lowercase() }
        .mapValues { it.value.size }

    val datosPorPeso: Map<String, Int> = semillasEnLote.groupBy { semilla ->
        when {
            semilla.weight >= 0.0 && semilla.weight <= 20.0 -> "0g-20g"  // Rango inclusivo
            semilla.weight > 20.0 && semilla.weight<= 40.0 -> "21g-40g " // Ajusta los límites como necesites
            semilla.weight > 40.0 && semilla.weight <= 60.0 -> "41g-60g"
            semilla.weight > 60.0 && semilla.weight <= 80.0 -> "61g-80g"
            semilla.weight > 80.0 && semilla.weight <= 100.0 -> "81g-100g" // Asumiendo un límite superior
            else -> {
                // Decidir qué hacer con pesos fuera de los rangos esperados
                // Podría ser "Otros", o si esperas que todos caigan en 0-100:
                if (semilla.weight > 100.0) "81-100" // O ">100"
                else if (semilla.weight < 0.0) "<0" // O agrupar negativos en "0-20"
                else "Desconocido" // O lanzar una excepción si es un estado inválido
            }
        }
    }.mapValues { entry ->
        entry.value.size
    }

    Column(
        modifier = Modifier
            .fillMaxWidth()

    ) {



        Spacer(modifier = Modifier.height(16.dp))


        Text(
            "Distribución por Estado",
            style = MaterialTheme.typography.titleMedium,
            modifier = Modifier.padding(vertical = 8.dp)
        )
        GraficoCircular(
            datos = datosPorEstado,
            modifier = Modifier
                .fillMaxWidth()
                .height(200.dp)
                .padding(8.dp)
        )
        Text(
            "Distribución por Tamaño",
            style = MaterialTheme.typography.titleMedium,
            modifier = Modifier.padding(vertical = 8.dp)
        )
        GraficoBarras(
            datos = datosPorTamaño,
            modifier = Modifier
                .fillMaxWidth()
                .padding(8.dp),
            titulo = "Distribución por Tamaño"
        )

        // Gráfico de colores
        Text(
            "Distribución por Color",
            style = MaterialTheme.typography.titleMedium,
            modifier = Modifier.padding(vertical = 8.dp)
        )
        GraficoCircular(
            datos = datosPorColor,
            modifier = Modifier
                .fillMaxWidth()
                .height(200.dp)
                .padding(8.dp)
        )

        Spacer(modifier = Modifier.height(16.dp))

        // Gráfico de pesos
        Text(
            "Distribución por Peso",
            style = MaterialTheme.typography.titleMedium,
            modifier = Modifier.padding(vertical = 8.dp)
        )
        GraficoBarras(
            datos = datosPorPeso,
            modifier = Modifier
                .fillMaxWidth()
                .padding(8.dp),
            titulo = "Distribución por Peso"
        )
    }
}

@Composable
fun DatosTab(semillasEnLote: List<Seed>) {
    if (semillasEnLote.isEmpty()) {
        Box(
            modifier = Modifier
                .fillMaxWidth()
                .height(200.dp),
            contentAlignment = Alignment.Center
        ) {
            Text(
                "No hay datos para mostrar",
                color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.5f)
            )
        }
        return
    }

    Column(
        modifier = Modifier
            .fillMaxWidth()
            .padding(vertical = 4.dp)
    ) {
        semillasEnLote.forEachIndexed { index, semilla ->
            Card(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(vertical = 4.dp),
                elevation = CardDefaults.cardElevation(2.dp),
                colors = CardDefaults.cardColors(
                    containerColor = MaterialTheme.colorScheme.surfaceVariant
                )
            ) {
                Row(
                    modifier = Modifier.padding(12.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    // Indicador de color
                    Box(
                        modifier = Modifier
                            .size(16.dp)
                            .background(
                                color = when (semilla.color.lowercase()) {
                                    "malo" -> Color.Red
                                    "bueno" -> Color.Green
                                    else -> Color.Gray
                                },
                                shape = CircleShape
                            )
                    )

                    Spacer(modifier = Modifier.width(12.dp))

                    Column(modifier = Modifier.weight(1f)) {
                        Text(
                            text = "Semilla ${index + 1}",
                            style = MaterialTheme.typography.bodyMedium,
                            fontWeight = FontWeight.Medium
                        )

                        Text(
                            text = "Estado: ${semilla.status}, Color: ${semilla.color}, Tamaño: ${semilla.size}, Peso: ${semilla.weight}",
                            style = MaterialTheme.typography.bodySmall
                        )
                    }

                    Text(
                        text = semilla.registration_date,
                        style = MaterialTheme.typography.bodySmall,
                        color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.6f)
                    )
                }
            }
        }
    }
}

@Composable
fun GraficoCircular(
    datos: Map<String, Int>,
    modifier: Modifier = Modifier
) {
    val total = datos.values.sum().toFloat()
    if (total == 0f) return // Evitar división por cero

    Card(
        modifier = modifier,
        shape = RoundedCornerShape(12.dp),
        elevation = CardDefaults.cardElevation(8.dp)
    ) {
        Row(
            modifier = Modifier
                .padding(16.dp)
                .fillMaxWidth(),
            verticalAlignment = Alignment.CenterVertically
        ) {
            // --- Gráfico circular a la izquierda ---
            Canvas(
                modifier = Modifier
                    .size(150.dp)
            ) {
                var startAngle = 0f
                datos.entries.forEach { (key, value) ->
                    val sweepAngle = (value / total) * 360f
                    val color = when (key.lowercase()) {
                        "apto" -> Color(0xFF4CAF50)
                        "no apto" -> Color(0xFFF44336)
                        "bueno" -> Color(0xFF4CAF50)
                        "malo" -> Color(0xFFF44336)
                        else -> Color.Gray
                    }

                    drawArc(
                        color = color,
                        startAngle = startAngle,
                        sweepAngle = sweepAngle,
                        useCenter = true
                    )

                    // Texto dentro del sector
                    val angleMiddle = startAngle + sweepAngle / 2
                    val radius = size.minDimension / 3
                    val x = (size.width / 2 + radius * cos(Math.toRadians(angleMiddle.toDouble()))).toFloat()
                    val y = (size.height / 2 + radius * sin(Math.toRadians(angleMiddle.toDouble()))).toFloat()

                    drawContext.canvas.nativeCanvas.apply {
                        val texto = "$value"
                        drawText(
                            texto,
                            x,
                            y,
                            android.graphics.Paint().apply {
                                this.color = android.graphics.Color.BLACK
                                textSize = 28f
                                textAlign = android.graphics.Paint.Align.CENTER
                                isFakeBoldText = true
                            }
                        )
                    }

                    startAngle += sweepAngle
                }
            }

            Spacer(modifier = Modifier.width(24.dp))

            // --- Resumen a la derecha ---
            Column(
                verticalArrangement = Arrangement.Center
            ) {
                datos.entries.forEach { (key, value) ->
                    val porcentaje = ((value / total) * 100).roundToInt()
                    val color = when (key.lowercase()) {
                        "apto" -> Color(0xFF4CAF50)
                        "no apto" -> Color(0xFFF44336)
                        "bueno" -> Color(0xFF4CAF50)
                        "malo" -> Color(0xFFF44336)
                        else -> Color.Gray
                    }

                    Row(
                        verticalAlignment = Alignment.CenterVertically,
                        modifier = Modifier.padding(vertical = 4.dp)
                    ) {
                        Box(
                            modifier = Modifier
                                .size(16.dp)
                                .background(color, CircleShape)
                        )
                        Spacer(modifier = Modifier.width(8.dp))
                        Column {
                            Text(
                                text = key.replaceFirstChar { it.uppercase() },
                                style = MaterialTheme.typography.bodyMedium
                            )
                            Text(
                                text = "$value semillas (${porcentaje}%)",
                                style = MaterialTheme.typography.bodySmall.copy(
                                    fontWeight = FontWeight.Bold,
                                    color = Color.Gray
                                )
                            )
                        }
                    }
                }
            }
        }
    }
}

@Composable
fun GraficoBarras(
    datos: Map<String, Int>,
    modifier: Modifier = Modifier,
    colorBarras: Color = Color(0xFF4CAF50),
    colorTexto: Color = MaterialTheme.colorScheme.onSurface,
    mostrarPorcentajes: Boolean = true,
    titulo: String? = null,
    animarBarras: Boolean = true
) {
    val total = datos.values.sum().toFloat()

    // Si no hay datos
    if (total == 0f) {
        Card(
            modifier = modifier
                .fillMaxWidth()
                .heightIn(min = 200.dp),
            shape = RoundedCornerShape(12.dp),
            elevation = CardDefaults.cardElevation(8.dp)
        ) {
            Box(
                modifier = Modifier.fillMaxSize(),
                contentAlignment = Alignment.Center
            ) {
                Text(
                    text = "No hay datos disponibles",
                    style = MaterialTheme.typography.bodyMedium,
                    color = Color.Gray
                )
            }
        }
        return
    }

    // 🔥 Altura dinámica según la cantidad de categorías
    val alturaBasePorBarra = 60.dp           // altura ideal por barra
    val alturaCanvas = 200.dp                // altura de la zona del gráfico
    val alturaExtraEtiquetas = 40.dp         // espacio inferior para etiquetas
    val alturaCard = alturaCanvas + alturaExtraEtiquetas

    // Animación
    var animacionCompletada by remember { mutableStateOf(false) }
    val progresoAnimacion by animateFloatAsState(
        targetValue = if (animarBarras && animacionCompletada) 1f else 0f,
        animationSpec = tween(durationMillis = 800, easing = FastOutSlowInEasing),
        label = "animacion_barras"
    )

    LaunchedEffect(Unit) { animacionCompletada = true }

    Card(
        modifier = modifier
            .fillMaxWidth()
            .heightIn(min = alturaCard),  // 🔑 ajusta la altura mínima
        shape = RoundedCornerShape(12.dp),
        elevation = CardDefaults.cardElevation(8.dp)
    ) {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(16.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            // Título opcional
            titulo?.let {
                Text(
                    text = it,
                    style = MaterialTheme.typography.headlineSmall,
                    fontWeight = FontWeight.Bold,
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(bottom = 16.dp)
                )
            }

            // 🔑 Canvas dinámico
            Canvas(
                modifier = Modifier
                    .fillMaxWidth()
                    .height(alturaCanvas)
            ) {
                val maxValue = datos.values.maxOrNull()?.toFloat() ?: 1f
                val barWidth = (size.width - 32.dp.toPx()) / (datos.size * 2f)
                val spacing = barWidth / 2

                datos.entries.forEachIndexed { index, (key, value) ->
                    val barHeight = ((value / maxValue) * size.height * 0.8f) * progresoAnimacion
                    val x = index * (barWidth + spacing) + spacing + 16.dp.toPx()
                    val y = size.height - barHeight

                    // Barra
                    drawRoundRect(
                        color = colorBarras,
                        topLeft = Offset(x, y),
                        size = Size(barWidth, barHeight),
                        cornerRadius = CornerRadius(4.dp.toPx())
                    )

                    // Cantidad sobre la barra
                    if (barHeight > 20.dp.toPx()) {
                        drawContext.canvas.nativeCanvas.drawText(
                            value.toString(),
                            x + barWidth / 2,
                            y - 8.dp.toPx(),
                            android.graphics.Paint().apply {
                                color = colorTexto.toArgb()
                                textSize = 12.sp.toPx()
                                textAlign = android.graphics.Paint.Align.CENTER
                                isFakeBoldText = true
                                typeface = android.graphics.Typeface.DEFAULT_BOLD
                            }
                        )
                    }

                    // 🔑 Etiquetas visibles debajo de las barras
                    drawContext.canvas.nativeCanvas.drawText(
                        if (key.length > 8) "${key.take(8)}..." else key,
                        x + barWidth / 2,
                        size.height + 16.dp.toPx(),   // margen inferior
                        android.graphics.Paint().apply {
                            color = colorTexto.toArgb()
                            textSize = 10.sp.toPx()
                            textAlign = android.graphics.Paint.Align.CENTER
                        }
                    )
                }
            }

            Spacer(modifier = Modifier.height(24.dp))

            // 🔎 Resumen de datos
            Column(
                modifier = Modifier
                    .fillMaxWidth()

            ) {
                datos.entries.sortedByDescending { it.value }.forEach { (key, value) ->
                    val porcentaje = ((value / total) * 100)
                    val porcentajeFormateado = String.format("%.1f", porcentaje)

                    Row(
                        verticalAlignment = Alignment.CenterVertically,
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(vertical = 4.dp)
                    ) {
                        // Indicador de color
                        Box(
                            modifier = Modifier
                                .size(16.dp)
                                .background(colorBarras, CircleShape)
                        )

                        Spacer(modifier = Modifier.width(12.dp))

                        // Información
                        Column(modifier = Modifier.weight(1f)) {
                            Text(
                                text = key.replaceFirstChar {
                                    if (it.isLowerCase()) it.titlecase(Locale.getDefault())
                                    else it.toString()
                                },
                                style = MaterialTheme.typography.bodyLarge,
                                maxLines = 1,
                                overflow = TextOverflow.Ellipsis
                            )

                            Text(
                                text = if (mostrarPorcentajes) {
                                    "$value ${if (value == 1) "semilla" else "semillas"} ($porcentajeFormateado%)"
                                } else {
                                    "$value ${if (value == 1) "semilla" else "semillas"}"
                                },
                                style = MaterialTheme.typography.bodyMedium.copy(
                                    fontWeight = FontWeight.Medium,
                                    color = MaterialTheme.colorScheme.onSurfaceVariant
                                )
                            )
                        }

                        // Barra de porcentaje opcional
                        if (mostrarPorcentajes) {
                            Spacer(modifier = Modifier.width(8.dp))
                            Box(
                                modifier = Modifier
                                    .width(60.dp)
                                    .height(4.dp)
                                    .background(MaterialTheme.colorScheme.surfaceVariant)
                            ) {
                                Box(
                                    modifier = Modifier
                                        .height(4.dp)
                                        .width(60.dp * porcentaje / 100f)
                                        .background(colorBarras)
                                )
                            }
                        }
                    }
                }
            }
        }
    }
}


// Versión alternativa con colores diferentes para cada barra
@Composable
fun GraficoBarrasColorido(
    datos: Map<String, Int>,
    colores: List<Color> = listOf(
        Color(0xFF4CAF50),
        Color(0xFF2196F3),
        Color(0xFFFF9800),
        Color(0xFFF44336),
        Color(0xFF9C27B0),
        Color(0xFF607D8B)
    ),
    modifier: Modifier = Modifier
) {
    GraficoBarras(
        datos = datos,
        modifier = modifier,
        colorBarras = Color(0xFF4CAF50), // Color por defecto, se puede personalizar
        animarBarras = true
    )
}

// Preview para testing
@Preview(showBackground = true)
@Composable
fun PreviewGraficoBarras() {
    MaterialTheme {
        GraficoBarras(
            datos = mapOf(
                "Manzanas" to 150,
                "Naranjas" to 75,
                "Plátanos" to 200,
                "Uvas" to 50,
                "Fresas" to 125
            ),
            titulo = "Distribución de Semillas",
            modifier = Modifier.padding(16.dp)
        )
    }
}




@Composable
fun AnimatedCard(
    modifier: Modifier = Modifier,
    shape: Shape = CardDefaults.shape,
    colors: CardColors = CardDefaults.cardColors(),
    elevation: CardElevation = CardDefaults.cardElevation(defaultElevation = 2.dp), // Elevación por defecto para el Card
    content: @Composable ColumnScope.() -> Unit
) {
    Card(
        modifier = modifier,
        shape = shape,
        colors = colors,
        elevation = elevation, // Simplemente pasa el objeto CardElevation
        content = content
    )
}