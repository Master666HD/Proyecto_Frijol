package com.example.appfrijol.presentation.session

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.data.local.datastore.DataStoreManager
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.SharingStarted
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.map
import kotlinx.coroutines.flow.stateIn
import kotlinx.coroutines.launch
import javax.inject.Inject


@HiltViewModel
class SessionViewModel @Inject constructor(
    private val dataStoreManager: DataStoreManager
) : ViewModel() {

    val isLoggedIn: StateFlow<Boolean> = dataStoreManager.tokenFlow
        .map { !it.isNullOrEmpty() }
        .stateIn(viewModelScope, SharingStarted.Lazily, false)

    val userName: StateFlow<String> = dataStoreManager.userNameFlow
        .map { it ?: "Usuario" }
        .stateIn(viewModelScope, SharingStarted.Lazily, "Usuario")

    val userId: StateFlow<String> = dataStoreManager.userIdFlow
        .map { it ?: "" }
        .stateIn(viewModelScope, SharingStarted.Lazily, "")

    fun login(token: String, userName: String, userId: Any) {
        viewModelScope.launch {
            dataStoreManager.saveSession(token, userName, userId)
        }
    }


    fun logout() {
        viewModelScope.launch {
            dataStoreManager.clearSession()
        }
    }
}

