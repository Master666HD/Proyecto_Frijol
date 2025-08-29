package com.example.appfrijol.presentation.welcome

import androidx.compose.animation.core.LinearEasing
import androidx.compose.animation.core.RepeatMode
import androidx.compose.animation.core.animateFloat
import androidx.compose.animation.core.infiniteRepeatable
import androidx.compose.animation.core.rememberInfiniteTransition
import androidx.compose.animation.core.tween
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.systemBarsPadding
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.SideEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.graphicsLayer
import androidx.compose.ui.text.SpanStyle
import androidx.compose.ui.text.buildAnnotatedString
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import com.google.accompanist.systemuicontroller.rememberSystemUiController
import kotlinx.coroutines.delay

@Composable
fun WelcomeScreen(
    userName: String?,
    onLoginClick: () -> Unit
) {
    val logoText = "FRIJOLTECH"
    var visibleIndex by remember { mutableStateOf(0) }
    var showButton by remember { mutableStateOf(false) }

    // 👇 Controlador de barras del sistema
    val systemUiController = rememberSystemUiController()
    val backgroundColor = MaterialTheme.colorScheme.primary

    SideEffect {
        systemUiController.setSystemBarsColor(
            color = backgroundColor
        )
    }

    LaunchedEffect(Unit) {
        while (visibleIndex < logoText.length) {
            visibleIndex++
            delay(150)
        }
        showButton = true
    }

    Box(
        modifier = Modifier
            .fillMaxSize()
            .background(backgroundColor) // Fondo principal
            .systemBarsPadding(),        // respeta notch, status bar y nav bar
        contentAlignment = Alignment.Center
    ) {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .padding(horizontal = 24.dp),
            verticalArrangement = Arrangement.Center,
            horizontalAlignment = Alignment.CenterHorizontally
        ) {

            val annotatedText = buildAnnotatedString {
                if (visibleIndex > 0) {
                    val firstPart = logoText.substring(0, minOf(6, visibleIndex))
                    pushStyle(SpanStyle(color = MaterialTheme.colorScheme.tertiary, fontWeight = FontWeight.Bold))
                    append(firstPart)
                    pop()
                }
                if (visibleIndex > 6) {
                    val secondPart = logoText.substring(6, visibleIndex)
                    pushStyle(SpanStyle(color = MaterialTheme.colorScheme.secondary, fontWeight = FontWeight.Bold))
                    append(secondPart)
                    pop()
                }
            }

            Text(
                text = annotatedText,
                style = MaterialTheme.typography.headlineLarge,
            )

            Spacer(modifier = Modifier.height(48.dp))

            if (showButton) {
                // Creamos una transición infinita
                val infiniteTransition = rememberInfiniteTransition()
                val scale by infiniteTransition.animateFloat(
                    initialValue = 1f,
                    targetValue = 1.1f, // tamaño máximo del palpito
                    animationSpec = infiniteRepeatable(
                        animation = tween(600, easing = LinearEasing), // duración y easing
                        repeatMode = RepeatMode.Reverse
                    )
                )

                Button(
                    onClick = onLoginClick,
                    modifier = Modifier
                        .fillMaxWidth()
                        .height(60.dp)
                        .graphicsLayer { scaleX = scale; scaleY = scale },
                    shape = RoundedCornerShape(16.dp),
                    colors = ButtonDefaults.buttonColors(containerColor = MaterialTheme.colorScheme.background),
                    elevation = ButtonDefaults.buttonElevation(defaultElevation = 8.dp)
                ) {
                    Text(
                        text = if (!userName.isNullOrEmpty()) "Bienvenido $userName" else "Bienvenido",
                        color = MaterialTheme.colorScheme.secondary,
                        style = MaterialTheme.typography.titleMedium.copy(fontWeight = FontWeight.Bold)
                    )
                }
            }
        }
    }
}