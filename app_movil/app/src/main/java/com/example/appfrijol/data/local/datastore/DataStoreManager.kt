package com.example.appfrijol.data.local.datastore

import android.content.Context
import android.util.Log
import androidx.datastore.preferences.core.edit
import androidx.datastore.preferences.core.stringPreferencesKey
import androidx.datastore.preferences.preferencesDataStore
import com.example.appfrijol.data.remote.dataStore

import kotlinx.coroutines.flow.Flow
import kotlinx.coroutines.flow.map
import javax.inject.Inject
val Context.dataStore by preferencesDataStore(name = "auth_prefs")

class DataStoreManager @Inject constructor(
    private val context: Context
) {
    companion object {
        private val TOKEN = stringPreferencesKey("auth_token")
        private val USER_NAME = stringPreferencesKey("user_name")
        private val USER_ID = stringPreferencesKey("user_id")
        private val EMAIL = stringPreferencesKey("email")// 👈 nuevo
    }

    val tokenFlow: Flow<String?> = context.dataStore.data.map { preferences ->
        preferences[TOKEN]
    }

    val emailFlow: Flow<String?> = context.dataStore.data.map { preferences ->
        preferences[EMAIL]
    }

    val userNameFlow: Flow<String?> = context.dataStore.data.map { preferences ->
        preferences[USER_NAME]
    }

    val userIdFlow: Flow<String?> = context.dataStore.data.map { preferences ->
        preferences[USER_ID]
    }

    suspend fun saveSession(token: String, userName: String,email: String, userId: Any) {
        // Any si viene como Int
        Log.d("DataStore", "💾 saveSession() -> token=$token, userName=$userName, userId=$userId")
        context.dataStore.edit { preferences ->
            preferences[TOKEN] = token
            preferences[USER_NAME] = userName
            preferences[USER_ID] = userId.toString()
            preferences[EMAIL] = email // 🔑 siempre String
        }
    }

    suspend fun clearSession() {
        Log.d("DataStore", "🧹 clearSession() ejecutado")
        context.dataStore.edit { preferences ->
            preferences.clear()
        }
    }
    suspend fun clearToken() {
        context.dataStore.edit { preferences ->
            preferences[TOKEN] = ""
        }
    }

}

