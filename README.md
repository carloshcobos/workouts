# Workouts 84 Days Tracker

Aplicación web simple en **PHP + JSON + Bootstrap + JavaScript** para llevar el seguimiento de un plan de entrenamiento de 84 días.  
Permite registrar progreso diario, añadir/editar/eliminar ejercicios dinámicamente, marcar días como completados y guardar notas de cada sesión.

Funciona sin base de datos: toda la información se almacena en un único archivo `workouts.json`.

---

## Características principales

### **Gestión de entrenamientos**
- Visualización de los 84 días divididos automáticamente por semanas y fases  
- Cards individuales por día con:
  - Entreno AM (texto base + ejercicios personalizados)
  - Entreno PM (texto base + ejercicios personalizados)
  - Checkbox "realizado"
  - Notas por día
- Rutinas de referencia **Fuerza A** y **Fuerza B** en acordeón desplegable
- Porcentaje total del plan completado con barra de progreso

### **Gestión dinámica de ejercicios** 🆕
- **Añadir ejercicios** con botón "+ Añadir ejercicio AM/PM"
- **Eliminar ejercicios** individualmente con botón "×" rojo
- **Animaciones suaves** al añadir/eliminar (fade-in/fade-out)
- **Confirmación inteligente** solo si el ejercicio tiene contenido
- **Foco automático** en nuevos campos de ejercicio

### **Interfaz mejorada** 🎨
- **Iconos Bootstrap Icons** en toda la interfaz
- **Código de colores**: Azul para AM, Naranja para PM
- **Diseño responsive** adaptado a móvil, tablet y escritorio
- **Efectos visuales** suaves y profesionales
- **Badges de estado** (Completado/Pendiente)

### **Tecnología**
- Datos persistidos en un archivo `workouts.json`
- Sin base de datos, sin dependencias externas
- Código limpio con **separación lógica** (`src/`) y vista (`public/`)
- JavaScript vanilla minimalista (~40 líneas)

---

## Estructura del proyecto

```
proyecto/
├── public/
│   ├── index.php         # Vista principal con gestión dinámica
│   └── .htaccess          # Redirección raíz -> public/
├── src/
│   └── workouts.php       # Lógica de carga/guardado del JSON
├── data/
│   └── workouts.json      # Base de datos en JSON (se crea automáticamente)
└── README.md              # Este archivo
```

---

## Requisitos

- **PHP 7.4+** (funciona con XAMPP, WAMP, MAMP, etc.)
- **Servidor web** con soporte para `.htaccess` (Apache recomendado)
- Navegador moderno con soporte para Bootstrap 5.3 y JavaScript ES6

---

## Instalación

### **Opción 1: XAMPP (recomendado)**

1. **Descarga e instala XAMPP**  
   [https://www.apachefriends.org/](https://www.apachefriends.org/)

2. **Clona o descarga el proyecto**
   ```bash
   cd C:/xampp/htdocs/
   git clone https://tu-repositorio.git workouts
   ```
   O descomprime el ZIP en `C:/xampp/htdocs/workouts/`

3. **Inicia Apache desde el panel de XAMPP**

4. **Accede a la aplicación**  
   Abre tu navegador en: [http://localhost/workouts/](http://localhost/workouts/)

5. **El archivo `workouts.json` se creará automáticamente** en `data/` la primera vez que accedas

### **Opción 2: Servidor PHP integrado**

```bash
cd /ruta/al/proyecto
php -S localhost:8000 -t public
```

Abre tu navegador en: [http://localhost:8000/](http://localhost:8000/)

### **Opción 3: Servidor web existente**

1. Sube todos los archivos a tu servidor
2. Asegúrate de que el directorio `data/` tenga **permisos de escritura** (chmod 755 o 777)
3. Accede a tu dominio: `https://tudominio.com/workouts/`

---

## Uso de la aplicación

### **Añadir ejercicios personalizados**
1. En cada card de día, verás las secciones AM y PM
2. Haz clic en **"+ Añadir ejercicio AM"** o **"+ Añadir ejercicio PM"**
3. Escribe el ejercicio en el campo que aparece
4. Haz clic en **"Guardar cambios"** al finalizar

### **Eliminar ejercicios**
1. Haz clic en el botón **"×"** rojo junto al ejercicio
2. Confirma la eliminación si el campo tiene texto
3. El ejercicio se eliminará con animación suave

### **Marcar día como completado**
1. Activa el checkbox **"Marcar como realizado"**
2. Guarda los cambios
3. El badge cambiará de "Pendiente" a "Completado"

### **Ver rutinas de referencia**
- Haz clic en **"⚡ Fuerza A y B"** en la parte superior
- Se desplegará un acordeón con los ejercicios base

---

## Personalización

### **Modificar rutinas de referencia**

Edita `public/index.php` en la sección del acordeón (líneas ~120-145):

```php
<span class="fw-semibold text-primary">Fuerza plan A:</span>
<ul class="mt-2">
    <li>Tu ejercicio 1</li>
    <li>Tu ejercicio 2</li>
    <!-- Añade más aquí -->
</ul>
```

### **Cambiar plan base de 84 días**

Edita `src/workouts.php` en la función `generateDefault84Workouts()` (línea ~50+):

```php
// Modifica los textos de am_base y pm_base según tus necesidades
$workouts[] = [
    'dia'           => $i,
    'am_ejercicios' => [],  // Array vacío, se llena desde la interfaz
    'pm_ejercicios' => [],  // Array vacío, se llena desde la interfaz
    'realizado'     => false,
    'notas'         => ''
];
```

### **Ajustar colores de las secciones**

Edita el `<style>` en `public/index.php` (líneas ~25-35):

```css
.am-section {
    border-left: 3px solid #TU_COLOR; /* AM */
}
.pm-section {
    border-left: 3px solid #TU_COLOR; /* PM */
}
```

---

## Solución de problemas

### **No se guardan los cambios**
- Verifica que el directorio `data/` tenga permisos de escritura
- En Linux/Mac: `chmod 755 data/`
- En XAMPP Windows: el directorio suele tener permisos correctos por defecto

### **Error 500 al acceder**
- Revisa que PHP esté habilitado
- Verifica que el archivo `.htaccess` exista en la raíz
- Comprueba los logs de Apache: `xampp/apache/logs/error.log`

### **Los ejercicios no se eliminan**
- Asegúrate de que JavaScript esté habilitado en tu navegador
- Abre la consola del navegador (F12) y busca errores

### **No aparecen los iconos**
- Verifica tu conexión a internet (Bootstrap Icons se carga desde CDN)
- Si necesitas trabajar offline, descarga Bootstrap Icons localmente

---

## Backup de datos

El archivo `data/workouts.json` contiene toda tu información. Para hacer una copia de seguridad:

### **Manual**
```bash
cp data/workouts.json data/workouts_backup_$(date +%Y%m%d).json
```

### **Automático (próximamente)**
La aplicación incluirá una función de exportación automática con timestamp.

---

## Tecnologías utilizadas

- **Backend**: PHP 7.4+
- **Frontend**: 
  - Bootstrap 5.3.3 (CSS framework)
  - Bootstrap Icons 1.11.3
  - JavaScript ES6 (vanilla, sin frameworks)
- **Almacenamiento**: JSON (archivo plano)
- **Servidor**: Apache con mod_rewrite

---

## Mejoras futuras

- [ ] Filtros por semana / fase  
- [ ] Tema oscuro con toggle
- [ ] Drag & drop para reordenar ejercicios
- [ ] Autocompletado de ejercicios comunes
- [ ] Copia automática del JSON con timestamp (backups)
- [ ] Exportar progreso a CSV/PDF
- [ ] Gráficas de progreso semanal
- [ ] Añadir fotos de progreso por semana
- [ ] Sincronizar con una API externa o Google Drive
- [ ] PWA (instalable como app móvil)

---

## Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Haz un fork del repositorio
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Añade nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

---

## Licencia

Este proyecto está bajo licencia MIT. Puedes usarlo, modificarlo y distribuirlo libremente.

---

## Autor

Desarrollado con ❤️ para seguimiento de entrenamientos personales.

**¿Preguntas o sugerencias?** Abre un issue en el repositorio o contacta directamente.

---

## Changelog

### v2.0.0 (Diciembre 2024) - Gestión Dinámica
- Añadida gestión dinámica de ejercicios con JavaScript
- Botones para añadir/eliminar ejercicios individualmente
- Animaciones suaves en la interfaz
- Iconos Bootstrap Icons en toda la aplicación
- Código de colores para AM/PM
- Confirmación inteligente al eliminar
- Optimización del código PHP (filtrado de arrays)
- Mejoras en diseño responsive

### v1.0.0 (Inicial)
- Sistema base de seguimiento de 84 días
- Almacenamiento en JSON
- Bootstrap 5 responsive
- Rutinas de referencia en acordeón
- Progreso con barra porcentual