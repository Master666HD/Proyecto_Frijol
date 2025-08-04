package com.example.appfrijol.presentation.login

import android.util.Log
import androidx.compose.foundation.Image
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Lock
import androidx.compose.material.icons.filled.Person
import androidx.compose.material.icons.filled.Visibility
import androidx.compose.material.icons.filled.VisibilityOff
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.OutlinedTextField
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.res.painterResource
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.input.PasswordVisualTransformation
import androidx.compose.ui.text.input.VisualTransformation
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.lifecycle.viewmodel.compose.viewModel
import com.example.appfrijol.R
import com.example.appfrijol.domain.model.UserInfo



@Composable
fun LoginScreen(
    onLoginSuccess: (UserInfo) -> Unit,
    onIrARegistro: () -> Unit,
    loginViewModel: LoginViewModel = viewModel()
) {
    var usuario by remember { mutableStateOf("") }
    var contrasenia by remember { mutableStateOf("") }
    var verContrasenia by remember { mutableStateOf(false) }
    val cargando = loginViewModel.cargando
    val mensaje = loginViewModel.mensaje


    Column(
        modifier = Modifier
            .fillMaxSize()
            .padding(24.dp),
        verticalArrangement = Arrangement.Center,


        ) {
        Column(
            modifier = Modifier
                .fillMaxWidth(),
            verticalArrangement = Arrangement.Center,
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Image(
                painter = painterResource(id = R.drawable.logo),
                contentDescription = "Logo",
                modifier = Modifier.size(300.dp)
            )


        }


        Text(
            text = "Iniciar Sesión",
            style = MaterialTheme.typography.headlineMedium.copy(
                fontSize = 30.sp,
                fontWeight = FontWeight.Bold
            ),
            textAlign = TextAlign.Center
        )

        Spacer(modifier = Modifier.height(16.dp))

        OutlinedTextField(
            value = usuario,
            onValueChange = { usuario = it },
            label = { Text("Usuario") },
            leadingIcon = {
                Icon(Icons.Default.Person, contentDescription = "Ícono usuario")
            },
            modifier = Modifier.fillMaxWidth()
        )

        OutlinedTextField(
            value = contrasenia,
            onValueChange = { contrasenia = it },
            label = { Text("Contraseña") },
            leadingIcon = {
                Icon(Icons.Default.Lock, contentDescription = "Ícono candado")
            },
            trailingIcon = {
                IconButton(onClick = { verContrasenia = !verContrasenia }) {
                    Icon(
                        imageVector = if (verContrasenia) Icons.Default.Visibility else Icons.Default.VisibilityOff,
                        contentDescription = "Ver contraseña"
                    )
                }
            },
            visualTransformation = if (verContrasenia) VisualTransformation.None else PasswordVisualTransformation(),
            modifier = Modifier.fillMaxWidth(),

            )

        Spacer(modifier = Modifier.height(16.dp))

        Button(
            onClick = {
                Log.d("LoginScreen", "Botón Login presionado")
                loginViewModel.login(usuario, contrasenia) {
                        usuarioLogueado ->
                    onLoginSuccess(usuarioLogueado)
                }

            }
            ,
            colors = ButtonDefaults.buttonColors(
                containerColor = Color.Black,
                contentColor = Color.White
            ),
            enabled = !cargando,
            modifier = Modifier.fillMaxWidth()

        ) {
            Text("Iniciar Sesion")

        }
        if (cargando) {
            Text("Cargando...")
        }

        Spacer(modifier = Modifier.height(16.dp))
        Text(
            text = "¿No tienes cuenta? Regístrate",
            modifier = Modifier
                .clickable { onIrARegistro() }
                .padding(8.dp),
            color = Color.Blue
        )

        if (mensaje.isNotEmpty()) {
            Text(mensaje, color = Color.Red)
        }
    }
}