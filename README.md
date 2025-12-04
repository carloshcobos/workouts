# Workouts 84 Days Tracker

Aplicación web simple en **PHP + JSON + Bootstrap** para llevar el seguimiento de un plan de entrenamiento de 84 días.  
Permite registrar progreso diario, añadir o modificar ejercicios, marcar días como completados y guardar notas de cada sesión.

Funciona sin base de datos: toda la información se almacena en un único archivo `workouts.json`.

---

## Características principales

- ✔️ Visualización de los 84 días divididos automáticamente por semanas y fases  
- ✔️ Cards individuales por día con:
  - Entreno AM (texto base + ejercicios personalizados)
  - Entreno PM (texto base + ejercicios personalizados)
  - Checkbox “realizado”
  - Notas por día
- ✔️ Rutinas de referencia **Fuerza A** y **Fuerza B**
- ✔️ Porcentaje total del plan completado
- ✔️ Datos persistidos en un archivo `workouts.json`
- ✔️ Sin base de datos, sin dependencias externas
- ✔️ Código limpio con **separación lógica** (`src/`) y vista (`public/`)

---

## Estructura del proyecto

```
workouts/
│
├── public/
│   └── index.php        # Vista principal (Bootstrap)
│
├── src/
│   └── workouts.php     # Lógica del plan + lectura/escritura JSON
│
├── workouts.json        # Archivo generado automáticamente
└── .htaccess            # Redirección automática hacia public/index.php
```

---

## ¿Cómo funciona el almacenamiento?

Todo se guarda en:

```
workouts.json
```

Cada día del plan tiene esta estructura:

```json
{
  "dia": 1,
  "am_base": "Caminata 30 min FC 110–120",
  "pm_base": "Movilidad + estiramientos",
  "am_ejercicios": ["Prensa 3x12"],
  "pm_ejercicios": [],
  "realizado": false,
  "notas": ""
}
```

### Lógica del proyecto

Toda la lógica del plan y manejo del JSON está en:

```
src/workouts.php
```

Incluye:

- Creación inicial de los 84 días  
- Lectura / escritura del JSON  
- Cálculo de semana y fase  
- Guardado de ejercicios personalizados  

### Vista

Todo el HTML + Bootstrap está en:

```
public/index.php
```

Incluye:

- Render de cards  
- Formularios de edición por día  
- Mostrar progreso global  
- Mostrar rutinas Fuerza A y B  

---

## Mejoras futuras

- [ ] Filtros por semana / fase  
- [ ] Tema oscuro  
- [ ] Copia automática del JSON con timestamp  
- [ ] Exportar progreso a CSV  
- [ ] Añadir fotos de progreso  
- [ ] Sincronizar con una API externa o Google Drive  

---

## Licencia

Uso personal y educativo. Puedes modificarlo libremente.

---

## Autor

Proyecto personal desarrollado para seguimiento de entrenamiento y mejora física.  
Siéntete libre de adaptarlo a tus propias rutinas.
