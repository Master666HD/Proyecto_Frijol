package com.example.appfrijol

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import com.example.appfrijol.navigation.AppNavigator
import com.example.appfrijol.ui.theme.AppFrijolTheme
import dagger.hilt.android.AndroidEntryPoint

@AndroidEntryPoint
class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContent {
            AppFrijolTheme {
                AppNavigator()
            }
        }
    }
}







