# Documento de Definición y Análisis del Proyecto

## 1. Resumen Ejecutivo
El **Sistema de Gestión y Seguimiento de Aplicativos** es una solución web de grado profesional diseñada para centralizar, monitorear y gestionar el inventario de aplicativos y el ciclo de vida de las actividades asociadas a los aplicativos dentro de una organización.

---

## 2. Definición del Problema y Objetivos
### 2.1 Problemática Afrontada
Las organizaciones suelen enfrentar fragmentación de la información referente a sus sistemas, lo que dificulta la toma de decisiones estratégicas y la visibilidad del estado real de los aplicativos.

### 2.2 Objetivos Principales
* **Centralización:** Mantener un inventario estructurado de aplicativos y planificacion de actividades.
* **Seguimiento Automatizado:** Calcular dinámicamente el progreso de cada aplicativo a partir de sus actividades dependientes.
* **Seguridad y Control de Accesos:** Garantizar la autenticación segura mediante tokens JWT y un modelo RBAC (Control de Acceso Basado en Roles) granular.
* **Interoperabilidad:** Exponer una API RESTful limpia, estructurada y predecible para ser consumida por clientes frontend modernos (Vue 3 + TypeScript).

---

## 3. Dominio del Negocio y Reglas Clave

### 3.1 Entidades Principales y Relaciones
1. **Gerencia:** Entidad que posee múltiples Usuarios y Aplicativos. Además, puede participar como colaboradora en una actividad.
2. **Usuario:** Cuenta de acceso al sistema vinculada a una Gerencia y a múltiples Roles/Permisos.
3. **Área:**  Clasificacion predefinida que clasifica las actividades dentro del ciclo de vida del sistema.
4. **Aplicativo:** Sistema o software inventariado. Pertenece a una Gerencia y se compone de múltiples Actividades.
5. **Actividad:** Tarea específica asignada a un Aplicativo y un Área. Registra avance percentil (0-100%) y puede involucrar a múltiples Gerencias Colaboradoras.
6. **Project:Manager:** Planificacion de actividades ordenadas por area, relacionadas con el ciclo de vida de un aplicativo.

### 3.2 Reglas Matemáticas y de Automatización (Project Manager)
* **Fórmula del Avance Total de un Aplicativo:**
  $$Avance\ Total = \frac{\sum_{i=1}^{n} (\text{Porcentaje Actividad}_i)}{n}$$
  *(Donde $n$ es el número total de actividades registradas para el aplicativo).*
* **Restricción de Porcentaje:** Cada actividad registra un porcentaje entero restringido estrictamente al rango $[0, 100]$.
* **Checklist Automatizado:** El cálculo del avance total es dinámico y se revalúa en tiempo real ante cualquier operación de creación, actualización o eliminación de actividades.

---

## 4. Matriz de Roles y Permisos (RBAC)

El sistema soporta permisos granulares divididos en módulos de lectura (`ver.*`) y de gestión (`gestionar.*`):

| Rol | Permisos Asignados | Alcance Operativo |
| :--- | :--- | :--- |
| **Administrador** | Todos los permisos (`ver.*`, `gestionar.*`) | Acceso y gestion total del sistema |
| **Colaborador** | `ver.aplicativos`, `ver.actividades`, `ver.gerencias` | Consulta de información |

---

## 5. Requerimientos Funcionales y No Funcionales

### 5.1 Requerimientos Funcionales (RF)
* **RF-01 (Autenticación):** Inicio de sesión con credenciales únicas y obtención de token JWT.
* **RF-02 (Gestión de Usuarios):** CRUD de usuarios con asignación de roles y vinculación a gerencias.
* **RF-03 (Gestión de Roles):** Consulta de permisos y asignación dinámica de permisos a roles.
* **RF-04 (Gestión de Aplicativos):** Inventariado de aplicativos indicando detalles técnicos.
* **RF-05 (Gestión de Actividades):** Creación y actualización de tareas asociadas a aplicativos, registrando progreso y gerencias colaboradoras.
* **RF-06 (Gestión de Project:Manager):** Reflejo de cálculos visuales de avance en tiempo real.

### 5.2 Requerimientos No Funcionales (RNF)
* **RNF-01 (Arquitectura):** Separación total entre Backend (API REST) y Frontend (SPA).
* **RNF-02 (Seguridad):** Cifrado de contraseñas (`Bcrypt`), expiración de tokens JWT y middleware RBAC en cada ruta API.
* **RNF-03 (Validación Unificada):** Uso de metodos para sanitización y validación de datos en el servidor.
* **RNF-04 (Estándar de Respuestas):** Estructura JSON estándar para respuestas exitosas y de error.