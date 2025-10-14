package com.example.appfrijol.navigation

import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.material3.CenterAlignedTopAppBar
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.material3.TopAppBarDefaults
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.SpanStyle
import androidx.compose.ui.text.buildAnnotatedString
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.text.withStyle
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun MyAppTopAppBar() {
    CenterAlignedTopAppBar(
        modifier = Modifier.height(70.dp),
        title = {
            Text(
                text = buildAnnotatedString {
                    // Parte blanca: "FRIJOL"
                    withStyle(
                        style = SpanStyle(
                            color = Color.White,
                            fontWeight = FontWeight.Bold
                        )
                    ) {
                        append("FRIJOL")
                    }

                    withStyle(
                        style = SpanStyle(
                            color = Color(0xFF18520C),
                            fontWeight = FontWeight.Bold
                        )
                    ) {
                        append("TECH")
                    }
                },
                style = MaterialTheme.typography.titleMedium.copy(
                    fontSize = 28.sp
                ),
                textAlign = TextAlign.Center, // 🔹 Asegura centrado del texto
                modifier = Modifier.fillMaxWidth() // 🔹 Ocupa todo el ancho
            )
        },
        colors = TopAppBarDefaults.centerAlignedTopAppBarColors(
            containerColor = MaterialTheme.colorScheme.primary,
            titleContentColor = MaterialTheme.colorScheme.onPrimary
        )
    )
}
