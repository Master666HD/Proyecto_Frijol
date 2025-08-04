package com.example.appfrijol

import android.os.Bundle
import android.util.Log
import android.widget.Toast
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.material3.MaterialTheme
import androidx.lifecycle.ViewModel
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.viewmodel.compose.viewModel
import com.example.appfrijol.data.local.datastore.DataStoreManager
import com.example.appfrijol.data.remote.RetrofitClient
import com.example.appfrijol.data.repository.AuthRepository
import com.example.appfrijol.presentation.login.LoginScreen
import com.example.appfrijol.presentation.login.LoginViewModel
import com.example.appfrijol.ui.theme.AppFrijolTheme

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()

        // 1. Inicialización de dependencias
        val apiService = RetrofitClient.apiService
        val authRepository = AuthRepository(apiService)
        val dataStoreManager = DataStoreManager(applicationContext)

        setContent {
            AppFrijolTheme {
                LoginScreen(
                    onLoginSuccess = { user ->
                        Log.d("APP_FLOW", "✅ Login exitoso - Usuario: ${user.firstName}")
                        Toast.makeText(
                            this@MainActivity,
                            "¡Bienvenido ${user.firstName}!",
                            Toast.LENGTH_SHORT
                        ).show()
                        // Aquí iría la navegación a Home
                    },
                    onIrARegistro = {
                        Log.d("APP_FLOW", "🔄 Navegando a registro")
                        Toast.makeText(
                            this@MainActivity,
                            "Redirigiendo a registro",
                            Toast.LENGTH_SHORT
                        ).show()
                        // Aquí iría la navegación a Registro
                    },
                    loginViewModel = viewModel(
                        factory = object : ViewModelProvider.Factory {
                            override fun <T : ViewModel> create(modelClass: Class<T>): T {
                                @Suppress("UNCHECKED_CAST")
                                return LoginViewModel(authRepository, dataStoreManager) as T
                            }
                        }
                    )
                )
            }
        }
    }
}