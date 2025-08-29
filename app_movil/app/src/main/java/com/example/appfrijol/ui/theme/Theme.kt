package com.example.appfrijol.ui.theme


import androidx.compose.foundation.isSystemInDarkTheme
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.darkColorScheme
import androidx.compose.material3.lightColorScheme
import androidx.compose.runtime.Composable

private val DarkColorScheme = darkColorScheme(
    primary = VerdeApp,
    secondary = BlancoApp,
    tertiary = NegroApp,
    background = NegroApp,
    surface = NegroApp,
    onPrimary = BlancoApp,
    onSecondary = NegroApp,
    onTertiary = BlancoApp,
    onBackground = BlancoApp,
    onSurface = BlancoApp
)

private val LightColorScheme = lightColorScheme(
    primary = VerdeApp,
    secondary = NegroApp,
    tertiary = BlancoApp,
    background = BlancoApp,
    surface = BlancoApp,
    onPrimary = BlancoApp,
    onSecondary = BlancoApp,
    onTertiary = NegroApp,
    onBackground = NegroApp,
    onSurface = NegroApp
)


@Composable
fun AppFrijolTheme(
    darkTheme: Boolean = isSystemInDarkTheme(),
    content: @Composable () -> Unit
) {
    val colorScheme = if (darkTheme) {
        DarkColorScheme
    } else {
        LightColorScheme
    }

    MaterialTheme(
        colorScheme = colorScheme,
        typography = Typography,
        content = content
    )
}
