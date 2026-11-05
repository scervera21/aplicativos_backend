# Documento de Arquitectura Backend

## 1. Stack Tecnológico y Entorno
* **Framework:** Laravel 10.x (PHP 8.2+)
* **Base de Datos:** PostgreSQL 16
* **Autenticación:** `tymon/jwt-auth` v2.x
* **Control de Acceso (RBAC):** `spatie/laravel-permission` v6.x
* **Patrón Arquitectónico:** API RESTful orientada a recursos con controladores desacoplados y Form Requests unificados.

---

## 2. Diagrama Entidad-Relación (Lógico)

```
[Gerencias] 1 --- N [Usuarios] N --- M [Roles (Spatie)]
    |
    | 1
    |
    +----------- N [Aplicativos] 1 --- N [Actividades] N --- M [Gerencias (Colaboradoras)]
                                              |
                                              | N
                                              | 1
                                           [Áreas]
```

---

## 3. Especificación de la Capa de Modelos (Eloquent)

### 3.1 Modelo `Usuario` (`App\Models\Usuario`)
* **Tabla:** `usuarios`
* **Interfaces:** `Tymon\JWTAuth\Contracts\JWTSubject`
* **Traits:** `Spatie\Permission\Traits\HasRoles`

### 3.2 Modelo `Aplicativo` (`App\Models\Aplicativo`)
* **Tabla:** `aplicativos`
* **Accessor Clave (`avance_total`):**
  Calcula dinámicamente el promedio del progreso percentil de las actividades vinculadas:
  ```php
  public function getAvanceTotalAttribute(): float
  {
      $totalActividades = $this->actividades()->count();
      if ($totalActividades === 0) return 0;

      $sumaPorcentajes = $this->actividades()->sum('porcentaje');
      return (int) round($sumaPorcentajes / $totalActividades, 2);
  }
  ```

### 3.3 Modelo `Actividad` (`App\Models\Actividad`)
* **Tabla:** `actividades`
* **Relación N:M:** Vincular gerencias secundarias de apoyo a través de la tabla pivote `colaboradores` (`gerenciasColaboradoras()`).

---

## 4. Capa de Validaciones: Form Requests Unificados

Para cumplir con el principio DRY (*Don't Repeat Yourself*), las operaciones de creación (`POST`) y actualización (`PUT/PATCH`) de cada recurso se centralizan en un único Form Request que detecta el método HTTP:

* **`UsuarioRequest`:** Valida nombres, emails únicos (excluyendo el ID actual en updates) y asignación de roles.
* **`AplicativoRequest`:** Valida nombres, responsables, relaciones con gerencias y detalles tecnicos.
* **`ActividadRequest`:** Valida datos basicos como nombre, fecha de inicio, fecha de finalizacion, responsables, porcentaje, estado y gerencias colaboradoras.

---

## 5. Matriz de Endpoints y Middleware de Seguridad (`routes/api.php`)

Todas las rutas privadas requieren el middleware `auth:api` (JWT Bearer Token) bajo el prefijo `/api/v1` y validan permisos específicos de Spatie mediante el middleware `permission:<nombre_permiso>`.

### 5.1 Rutas de Autenticación
| Método | Ruta | Middleware | Descripción |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/v1/login` | Público | Autenticación y retorno de JWT |
| `GET` | `/api/v1/me` | `auth:api` | Obtener usuario autenticado y roles |
| `POST` | `/api/v1/logout` | `auth:api` | Invalidar token actual |
| `POST` | `/api/v1/refresh` | `auth:api` | Refrescar token de acceso |

### 5.2 Rutas del Sistema (CRUDs de Recursos)
| Recurso | Método HTTP | Ruta | Middleware de Permiso |
| :--- | :--- | :--- | :--- |
| **Roles** | `GET` | `/api/v1/permisos` | `permission:ver.roles` |
| | `GET` \| `POST` | `/api/v1/roles` | `permission:ver.roles` \| `permission:gestionar.roles` |
| | `GET` \| `PUT/PATCH` \| `DELETE` | `/api/v1/roles/{id}` | `permission:ver.roles` \| `permission:gestionar.roles` |
| **Usuarios** | `GET` \| `POST` | `/api/v1/usuarios` | `permission:ver.usuarios` \| `permission:gestionar.usuarios` |
| | `GET` \| `PUT/PATCH` \| `DELETE` | `/api/v1/usuarios/{id}` | `permission:ver.usuarios` \| `permission:gestionar.usuarios` |
| **Gerencias** | `GET` \| `POST` | `/api/v1/gerencias` | `permission:ver.gerencias` \| `permission:gestionar.gerencias` |
| | `GET` \| `PUT/PATCH` \| `DELETE` | `/api/v1/gerencias/{id}` | `permission:ver.gerencias` \| `permission:gestionar.gerencias` |
| **Aplicativos**| `GET` \| `POST` | `/api/v1/aplicativos` | `permission:ver.aplicativos` \| `permission:gestionar.aplicativos` |
| | `GET` \| `PUT/PATCH` \| `DELETE` | `/api/v1/aplicativos/{id}` | `permission:ver.aplicativos` \| `permission:gestionar.aplicativos` |
| **Actividades**| `GET` \| `POST` | `/api/v1/actividades` | `permission:ver.actividades` \| `permission:gestionar.actividades` |
| | `GET` \| `PUT/PATCH` \| `DELETE` | `/api/v1/actividades/{id}` | `permission:ver.actividades` \| `permission:gestionar.actividades` |

---

## 6. Estándar de Respuestas JSON

El backend retorna un formato predecible para simplificar la integración con el cliente en Vue 3:

```json
{
  "status": "success",
  "message": "Mensaje descriptivo opcional",
  "data": { ... }
}
```