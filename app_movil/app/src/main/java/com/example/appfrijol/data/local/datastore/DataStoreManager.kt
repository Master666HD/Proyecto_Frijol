package com.example.appfrijol.data.local.datastore

import android.content.Context
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
        private val USER_ID = stringPreferencesKey("user_id") // 👈 nuevo
    }

    val tokenFlow: Flow<String?> = context.dataStore.data.map { preferences ->
        preferences[TOKEN]
    }

    val userNameFlow: Flow<String?> = context.dataStore.data.map { preferences ->
        preferences[USER_NAME]
    }

    val userIdFlow: Flow<String?> = context.dataStore.data.map { preferences ->
        preferences[USER_ID]
    }

    suspend fun saveSession(token: String, userName: String, userId: Any) { // Any si viene como Int
        context.dataStore.edit { preferences ->
            preferences[TOKEN] = token
            preferences[USER_NAME] = userName
            preferences[USER_ID] = userId.toString() // 🔑 siempre String
        }
    }

    suspend fun clearSession() {
        context.dataStore.edit { preferences ->
            preferences.clear()
        }
    }
}

