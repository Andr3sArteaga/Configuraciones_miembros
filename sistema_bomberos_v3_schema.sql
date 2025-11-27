--
-- PostgreSQL database dump
--

\restrict unN5JVHqQ12x6ipqLmwdpQonAgFRTgmTU9paGh4brUs87EmS5TKDNH9QdP0ObDB

-- Dumped from database version 17.6 (Homebrew)
-- Dumped by pg_dump version 17.6 (Homebrew)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: postgis; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;


--
-- Name: EXTENSION postgis; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION postgis IS 'PostGIS geometry and geography spatial types and functions';


--
-- Name: uuid-ossp; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;


--
-- Name: EXTENSION "uuid-ossp"; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';


--
-- Name: actualizar_cantidad_integrantes(); Type: FUNCTION; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE FUNCTION public.actualizar_cantidad_integrantes() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        UPDATE equipos 
        SET cantidad_integrantes = (SELECT COUNT(*) FROM miembros_equipo WHERE id_equipo = NEW.id_equipo)
        WHERE id = NEW.id_equipo;
    ELSIF TG_OP = 'DELETE' THEN
        UPDATE equipos 
        SET cantidad_integrantes = (SELECT COUNT(*) FROM miembros_equipo WHERE id_equipo = OLD.id_equipo)
        WHERE id = OLD.id_equipo;
    END IF;

    IF TG_OP = 'DELETE' THEN
        RETURN OLD;
    END IF;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.actualizar_cantidad_integrantes() OWNER TO angelfrederickpizaojeda;

--
-- Name: generar_codigo_recurso(); Type: FUNCTION; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE FUNCTION public.generar_codigo_recurso() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF NEW.codigo IS NULL THEN
        NEW.codigo = 'REC-' || to_char(CURRENT_TIMESTAMP, 'YYYYMMDD') || '-' ||
                     LPAD(((EXTRACT(EPOCH FROM CURRENT_TIMESTAMP))::INT % 10000)::TEXT, 4, '0');
    END IF;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.generar_codigo_recurso() OWNER TO angelfrederickpizaojeda;

--
-- Name: update_timestamp(); Type: FUNCTION; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE FUNCTION public.update_timestamp() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.actualizado = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.update_timestamp() OWNER TO angelfrederickpizaojeda;

--
-- Name: validar_entidad_tipo(); Type: FUNCTION; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE FUNCTION public.validar_entidad_tipo() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF NEW.entidad_tipo NOT IN ('usuario', 'comunario') THEN
        RAISE EXCEPTION 'Valor inválido para entidad_tipo: %. Debe ser "usuario" o "comunario".', NEW.entidad_tipo;
    END IF;

    RETURN NEW;
END;
$$;


ALTER FUNCTION public.validar_entidad_tipo() OWNER TO angelfrederickpizaojeda;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: comunarios_apoyo; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.comunarios_apoyo (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    nombre character varying(200) NOT NULL,
    edad integer,
    entidad_perteneciente character varying(200),
    equipoid uuid,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT comunarios_apoyo_edad_check CHECK (((edad >= 0) AND (edad <= 120)))
);


ALTER TABLE public.comunarios_apoyo OWNER TO angelfrederickpizaojeda;

--
-- Name: condiciones_climaticas; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.condiciones_climaticas (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    codigo character varying(50) NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text,
    factor_riesgo integer,
    activo boolean DEFAULT true,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT condiciones_climaticas_factor_riesgo_check CHECK (((factor_riesgo >= 1) AND (factor_riesgo <= 10)))
);


ALTER TABLE public.condiciones_climaticas OWNER TO angelfrederickpizaojeda;

--
-- Name: cursos; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.cursos (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    nombre character varying(200) NOT NULL,
    descripcion text,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.cursos OWNER TO angelfrederickpizaojeda;

--
-- Name: cursos_asignados; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.cursos_asignados (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    curso_id uuid,
    entidad_id uuid NOT NULL,
    entidad_tipo character varying(50) NOT NULL,
    fecha_asignacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.cursos_asignados OWNER TO angelfrederickpizaojeda;

--
-- Name: equipos; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.equipos (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    nombre_equipo character varying(100) NOT NULL,
    ubicacion public.geography(Point,4326),
    cantidad_integrantes integer DEFAULT 0,
    estado_id uuid,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.equipos OWNER TO angelfrederickpizaojeda;

--
-- Name: estados_sistema; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.estados_sistema (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    tabla character varying(50) NOT NULL,
    codigo character varying(50) NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text,
    color character varying(7),
    es_final boolean DEFAULT false,
    orden integer,
    activo boolean DEFAULT true,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.estados_sistema OWNER TO angelfrederickpizaojeda;

--
-- Name: focos_calor; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.focos_calor (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    latitude numeric(10,8) NOT NULL,
    longitude numeric(11,8) NOT NULL,
    confidence integer,
    acq_date date NOT NULL,
    acq_time time without time zone NOT NULL,
    bright_ti4 numeric(8,2),
    bright_ti5 numeric(8,2),
    frp numeric(8,2),
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.focos_calor OWNER TO angelfrederickpizaojeda;

--
-- Name: generos; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.generos (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    codigo character varying(20) NOT NULL,
    descripcion character varying(50),
    activo boolean DEFAULT true,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.generos OWNER TO angelfrederickpizaojeda;

--
-- Name: miembros_equipo; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.miembros_equipo (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    id_equipo uuid,
    id_usuario uuid,
    fecha_ingreso timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    es_lider boolean DEFAULT false NOT NULL
);


ALTER TABLE public.miembros_equipo OWNER TO angelfrederickpizaojeda;

--
-- Name: niveles_entrenamiento; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.niveles_entrenamiento (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    nivel character varying(50) NOT NULL,
    descripcion text,
    orden integer,
    activo boolean DEFAULT true,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.niveles_entrenamiento OWNER TO angelfrederickpizaojeda;

--
-- Name: niveles_gravedad; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.niveles_gravedad (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    codigo character varying(20) NOT NULL,
    nombre character varying(50) NOT NULL,
    descripcion text,
    orden integer,
    color character varying(7),
    activo boolean DEFAULT true,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.niveles_gravedad OWNER TO angelfrederickpizaojeda;

--
-- Name: noticias_incendios; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.noticias_incendios (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    title character varying(500) NOT NULL,
    date date NOT NULL,
    description text,
    url character varying(1000),
    image character varying(1000),
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.noticias_incendios OWNER TO angelfrederickpizaojeda;

--
-- Name: recursos; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.recursos (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    codigo character varying(50),
    tipo_recurso_id uuid,
    descripcion text NOT NULL,
    cantidad numeric(10,2),
    fecha_pedido timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    estado_id uuid,
    lat numeric(10,8),
    lng numeric(11,8),
    equipoid uuid,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.recursos OWNER TO angelfrederickpizaojeda;

--
-- Name: reportes; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.reportes (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    nombre_reportante character varying(200) NOT NULL,
    telefono_contacto character varying(20),
    fecha_hora timestamp without time zone NOT NULL,
    nombre_lugar character varying(200),
    ubicacion public.geography(Point,4326),
    tipo_incidente_id uuid,
    gravedad_id uuid,
    comentario_adicional text,
    cant_bomberos integer DEFAULT 0,
    cant_paramedicos integer DEFAULT 0,
    cant_veterinarios integer DEFAULT 0,
    cant_autoridades integer DEFAULT 0,
    estado_id uuid,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.reportes OWNER TO angelfrederickpizaojeda;

--
-- Name: reportes_incendio; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.reportes_incendio (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    nombre_incidente character varying(200) NOT NULL,
    controlado boolean DEFAULT false,
    extension numeric(10,2),
    condicion_climatica_id uuid,
    equipos_en_uso text,
    numero_bomberos integer,
    necesita_mas_bomberos boolean DEFAULT false,
    apoyo_externo text,
    comentario_adicional text,
    fecha_creacion timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    id_usuario_creador uuid
);


ALTER TABLE public.reportes_incendio OWNER TO angelfrederickpizaojeda;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.roles (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    codigo character varying(50) NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text,
    permisos jsonb,
    activo boolean DEFAULT true,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.roles OWNER TO angelfrederickpizaojeda;

--
-- Name: tipos_incidente; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.tipos_incidente (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    codigo character varying(50) NOT NULL,
    nombre character varying(100) NOT NULL,
    descripcion text,
    color character varying(7),
    icono character varying(50),
    activo boolean DEFAULT true,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.tipos_incidente OWNER TO angelfrederickpizaojeda;

--
-- Name: tipos_recurso; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.tipos_recurso (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    codigo character varying(50) NOT NULL,
    nombre character varying(100) NOT NULL,
    categoria character varying(100),
    descripcion text,
    unidad_medida character varying(50),
    activo boolean DEFAULT true,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.tipos_recurso OWNER TO angelfrederickpizaojeda;

--
-- Name: tipos_sangre; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.tipos_sangre (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    codigo character varying(5) NOT NULL,
    descripcion character varying(50),
    activo boolean DEFAULT true,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.tipos_sangre OWNER TO angelfrederickpizaojeda;

--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TABLE public.usuarios (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    nombre character varying(100) NOT NULL,
    apellido character varying(100) NOT NULL,
    ci character varying(20) NOT NULL,
    fecha_nacimiento date NOT NULL,
    genero_id uuid,
    telefono character varying(20),
    email character varying(150) NOT NULL,
    password character varying(255) NOT NULL,
    tipo_sangre_id uuid,
    nivel_entrenamiento_id uuid,
    entidad_perteneciente character varying(200),
    rol_id uuid,
    estado_id uuid,
    debe_cambiar_password boolean DEFAULT true,
    reset_token character varying(255),
    reset_token_expires timestamp without time zone,
    creado timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    actualizado timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.usuarios OWNER TO angelfrederickpizaojeda;

--
-- Name: comunarios_apoyo comunarios_apoyo_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.comunarios_apoyo
    ADD CONSTRAINT comunarios_apoyo_pkey PRIMARY KEY (id);


--
-- Name: condiciones_climaticas condiciones_climaticas_codigo_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.condiciones_climaticas
    ADD CONSTRAINT condiciones_climaticas_codigo_key UNIQUE (codigo);


--
-- Name: condiciones_climaticas condiciones_climaticas_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.condiciones_climaticas
    ADD CONSTRAINT condiciones_climaticas_pkey PRIMARY KEY (id);


--
-- Name: cursos_asignados cursos_asignados_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.cursos_asignados
    ADD CONSTRAINT cursos_asignados_pkey PRIMARY KEY (id);


--
-- Name: cursos cursos_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.cursos
    ADD CONSTRAINT cursos_pkey PRIMARY KEY (id);


--
-- Name: equipos equipos_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.equipos
    ADD CONSTRAINT equipos_pkey PRIMARY KEY (id);


--
-- Name: estados_sistema estados_sistema_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.estados_sistema
    ADD CONSTRAINT estados_sistema_pkey PRIMARY KEY (id);


--
-- Name: estados_sistema estados_sistema_tabla_codigo_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.estados_sistema
    ADD CONSTRAINT estados_sistema_tabla_codigo_key UNIQUE (tabla, codigo);


--
-- Name: focos_calor focos_calor_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.focos_calor
    ADD CONSTRAINT focos_calor_pkey PRIMARY KEY (id);


--
-- Name: generos generos_codigo_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.generos
    ADD CONSTRAINT generos_codigo_key UNIQUE (codigo);


--
-- Name: generos generos_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.generos
    ADD CONSTRAINT generos_pkey PRIMARY KEY (id);


--
-- Name: miembros_equipo miembros_equipo_id_equipo_id_usuario_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.miembros_equipo
    ADD CONSTRAINT miembros_equipo_id_equipo_id_usuario_key UNIQUE (id_equipo, id_usuario);


--
-- Name: miembros_equipo miembros_equipo_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.miembros_equipo
    ADD CONSTRAINT miembros_equipo_pkey PRIMARY KEY (id);


--
-- Name: niveles_entrenamiento niveles_entrenamiento_nivel_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.niveles_entrenamiento
    ADD CONSTRAINT niveles_entrenamiento_nivel_key UNIQUE (nivel);


--
-- Name: niveles_entrenamiento niveles_entrenamiento_orden_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.niveles_entrenamiento
    ADD CONSTRAINT niveles_entrenamiento_orden_key UNIQUE (orden);


--
-- Name: niveles_entrenamiento niveles_entrenamiento_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.niveles_entrenamiento
    ADD CONSTRAINT niveles_entrenamiento_pkey PRIMARY KEY (id);


--
-- Name: niveles_gravedad niveles_gravedad_codigo_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.niveles_gravedad
    ADD CONSTRAINT niveles_gravedad_codigo_key UNIQUE (codigo);


--
-- Name: niveles_gravedad niveles_gravedad_orden_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.niveles_gravedad
    ADD CONSTRAINT niveles_gravedad_orden_key UNIQUE (orden);


--
-- Name: niveles_gravedad niveles_gravedad_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.niveles_gravedad
    ADD CONSTRAINT niveles_gravedad_pkey PRIMARY KEY (id);


--
-- Name: noticias_incendios noticias_incendios_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.noticias_incendios
    ADD CONSTRAINT noticias_incendios_pkey PRIMARY KEY (id);


--
-- Name: recursos recursos_codigo_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_codigo_key UNIQUE (codigo);


--
-- Name: recursos recursos_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_pkey PRIMARY KEY (id);


--
-- Name: reportes_incendio reportes_incendio_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.reportes_incendio
    ADD CONSTRAINT reportes_incendio_pkey PRIMARY KEY (id);


--
-- Name: reportes reportes_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.reportes
    ADD CONSTRAINT reportes_pkey PRIMARY KEY (id);


--
-- Name: roles roles_codigo_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_codigo_key UNIQUE (codigo);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: tipos_incidente tipos_incidente_codigo_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.tipos_incidente
    ADD CONSTRAINT tipos_incidente_codigo_key UNIQUE (codigo);


--
-- Name: tipos_incidente tipos_incidente_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.tipos_incidente
    ADD CONSTRAINT tipos_incidente_pkey PRIMARY KEY (id);


--
-- Name: tipos_recurso tipos_recurso_codigo_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.tipos_recurso
    ADD CONSTRAINT tipos_recurso_codigo_key UNIQUE (codigo);


--
-- Name: tipos_recurso tipos_recurso_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.tipos_recurso
    ADD CONSTRAINT tipos_recurso_pkey PRIMARY KEY (id);


--
-- Name: tipos_sangre tipos_sangre_codigo_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.tipos_sangre
    ADD CONSTRAINT tipos_sangre_codigo_key UNIQUE (codigo);


--
-- Name: tipos_sangre tipos_sangre_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.tipos_sangre
    ADD CONSTRAINT tipos_sangre_pkey PRIMARY KEY (id);


--
-- Name: usuarios usuarios_ci_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_ci_key UNIQUE (ci);


--
-- Name: usuarios usuarios_email_key; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_email_key UNIQUE (email);


--
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);


--
-- Name: idx_cursos_asignados_curso; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_cursos_asignados_curso ON public.cursos_asignados USING btree (curso_id);


--
-- Name: idx_cursos_asignados_entidad; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_cursos_asignados_entidad ON public.cursos_asignados USING btree (entidad_id);


--
-- Name: idx_cursos_asignados_tipo; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_cursos_asignados_tipo ON public.cursos_asignados USING btree (entidad_tipo);


--
-- Name: idx_cursos_nombre; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_cursos_nombre ON public.cursos USING btree (nombre);


--
-- Name: idx_equipos_estado; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_equipos_estado ON public.equipos USING btree (estado_id);


--
-- Name: idx_equipos_ubicacion; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_equipos_ubicacion ON public.equipos USING gist (ubicacion);


--
-- Name: idx_estados_sistema_tabla; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_estados_sistema_tabla ON public.estados_sistema USING btree (tabla);


--
-- Name: idx_focos_coordenadas; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_focos_coordenadas ON public.focos_calor USING btree (latitude, longitude);


--
-- Name: idx_focos_fecha; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_focos_fecha ON public.focos_calor USING btree (acq_date);


--
-- Name: idx_miembros_equipo_equipo; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_miembros_equipo_equipo ON public.miembros_equipo USING btree (id_equipo);


--
-- Name: idx_miembros_equipo_usuario; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_miembros_equipo_usuario ON public.miembros_equipo USING btree (id_usuario);


--
-- Name: idx_recursos_equipo; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_recursos_equipo ON public.recursos USING btree (equipoid);


--
-- Name: idx_recursos_estado; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_recursos_estado ON public.recursos USING btree (estado_id);


--
-- Name: idx_recursos_tipo; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_recursos_tipo ON public.recursos USING btree (tipo_recurso_id);


--
-- Name: idx_reportes_estado; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_reportes_estado ON public.reportes USING btree (estado_id);


--
-- Name: idx_reportes_fecha; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_reportes_fecha ON public.reportes USING btree (fecha_hora);


--
-- Name: idx_reportes_gravedad; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_reportes_gravedad ON public.reportes USING btree (gravedad_id);


--
-- Name: idx_reportes_tipo; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_reportes_tipo ON public.reportes USING btree (tipo_incidente_id);


--
-- Name: idx_reportes_ubicacion; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_reportes_ubicacion ON public.reportes USING gist (ubicacion);


--
-- Name: idx_tipos_incidente_activo; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_tipos_incidente_activo ON public.tipos_incidente USING btree (activo);


--
-- Name: idx_usuarios_ci; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_usuarios_ci ON public.usuarios USING btree (ci);


--
-- Name: idx_usuarios_estado; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_usuarios_estado ON public.usuarios USING btree (estado_id);


--
-- Name: idx_usuarios_genero; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_usuarios_genero ON public.usuarios USING btree (genero_id);


--
-- Name: idx_usuarios_rol; Type: INDEX; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE INDEX idx_usuarios_rol ON public.usuarios USING btree (rol_id);


--
-- Name: miembros_equipo actualizar_cantidad_integrantes_trigger; Type: TRIGGER; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TRIGGER actualizar_cantidad_integrantes_trigger AFTER INSERT OR DELETE ON public.miembros_equipo FOR EACH ROW EXECUTE FUNCTION public.actualizar_cantidad_integrantes();


--
-- Name: recursos generar_codigo_recurso_trigger; Type: TRIGGER; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TRIGGER generar_codigo_recurso_trigger BEFORE INSERT ON public.recursos FOR EACH ROW EXECUTE FUNCTION public.generar_codigo_recurso();


--
-- Name: cursos_asignados trg_validar_entidad_tipo; Type: TRIGGER; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TRIGGER trg_validar_entidad_tipo BEFORE INSERT OR UPDATE ON public.cursos_asignados FOR EACH ROW EXECUTE FUNCTION public.validar_entidad_tipo();


--
-- Name: equipos update_equipos_timestamp; Type: TRIGGER; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TRIGGER update_equipos_timestamp BEFORE UPDATE ON public.equipos FOR EACH ROW EXECUTE FUNCTION public.update_timestamp();


--
-- Name: recursos update_recursos_timestamp; Type: TRIGGER; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TRIGGER update_recursos_timestamp BEFORE UPDATE ON public.recursos FOR EACH ROW EXECUTE FUNCTION public.update_timestamp();


--
-- Name: roles update_roles_timestamp; Type: TRIGGER; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TRIGGER update_roles_timestamp BEFORE UPDATE ON public.roles FOR EACH ROW EXECUTE FUNCTION public.update_timestamp();


--
-- Name: tipos_incidente update_tipos_incidente_timestamp; Type: TRIGGER; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TRIGGER update_tipos_incidente_timestamp BEFORE UPDATE ON public.tipos_incidente FOR EACH ROW EXECUTE FUNCTION public.update_timestamp();


--
-- Name: tipos_recurso update_tipos_recurso_timestamp; Type: TRIGGER; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TRIGGER update_tipos_recurso_timestamp BEFORE UPDATE ON public.tipos_recurso FOR EACH ROW EXECUTE FUNCTION public.update_timestamp();


--
-- Name: usuarios update_usuarios_timestamp; Type: TRIGGER; Schema: public; Owner: angelfrederickpizaojeda
--

CREATE TRIGGER update_usuarios_timestamp BEFORE UPDATE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.update_timestamp();


--
-- Name: comunarios_apoyo comunarios_apoyo_equipoid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.comunarios_apoyo
    ADD CONSTRAINT comunarios_apoyo_equipoid_fkey FOREIGN KEY (equipoid) REFERENCES public.equipos(id) ON DELETE CASCADE;


--
-- Name: cursos_asignados cursos_asignados_curso_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.cursos_asignados
    ADD CONSTRAINT cursos_asignados_curso_id_fkey FOREIGN KEY (curso_id) REFERENCES public.cursos(id) ON DELETE CASCADE;


--
-- Name: equipos equipos_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.equipos
    ADD CONSTRAINT equipos_estado_id_fkey FOREIGN KEY (estado_id) REFERENCES public.estados_sistema(id);


--
-- Name: miembros_equipo miembros_equipo_id_equipo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.miembros_equipo
    ADD CONSTRAINT miembros_equipo_id_equipo_fkey FOREIGN KEY (id_equipo) REFERENCES public.equipos(id) ON DELETE CASCADE;


--
-- Name: miembros_equipo miembros_equipo_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.miembros_equipo
    ADD CONSTRAINT miembros_equipo_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES public.usuarios(id) ON DELETE CASCADE;


--
-- Name: recursos recursos_equipoid_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_equipoid_fkey FOREIGN KEY (equipoid) REFERENCES public.equipos(id) ON DELETE SET NULL;


--
-- Name: recursos recursos_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_estado_id_fkey FOREIGN KEY (estado_id) REFERENCES public.estados_sistema(id);


--
-- Name: recursos recursos_tipo_recurso_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.recursos
    ADD CONSTRAINT recursos_tipo_recurso_id_fkey FOREIGN KEY (tipo_recurso_id) REFERENCES public.tipos_recurso(id);


--
-- Name: reportes reportes_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.reportes
    ADD CONSTRAINT reportes_estado_id_fkey FOREIGN KEY (estado_id) REFERENCES public.estados_sistema(id);


--
-- Name: reportes reportes_gravedad_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.reportes
    ADD CONSTRAINT reportes_gravedad_id_fkey FOREIGN KEY (gravedad_id) REFERENCES public.niveles_gravedad(id);


--
-- Name: reportes_incendio reportes_incendio_condicion_climatica_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.reportes_incendio
    ADD CONSTRAINT reportes_incendio_condicion_climatica_id_fkey FOREIGN KEY (condicion_climatica_id) REFERENCES public.condiciones_climaticas(id);


--
-- Name: reportes_incendio reportes_incendio_id_usuario_creador_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.reportes_incendio
    ADD CONSTRAINT reportes_incendio_id_usuario_creador_fkey FOREIGN KEY (id_usuario_creador) REFERENCES public.usuarios(id);


--
-- Name: reportes reportes_tipo_incidente_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.reportes
    ADD CONSTRAINT reportes_tipo_incidente_id_fkey FOREIGN KEY (tipo_incidente_id) REFERENCES public.tipos_incidente(id);


--
-- Name: usuarios usuarios_estado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_estado_id_fkey FOREIGN KEY (estado_id) REFERENCES public.estados_sistema(id);


--
-- Name: usuarios usuarios_genero_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_genero_id_fkey FOREIGN KEY (genero_id) REFERENCES public.generos(id);


--
-- Name: usuarios usuarios_nivel_entrenamiento_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_nivel_entrenamiento_id_fkey FOREIGN KEY (nivel_entrenamiento_id) REFERENCES public.niveles_entrenamiento(id);


--
-- Name: usuarios usuarios_rol_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_rol_id_fkey FOREIGN KEY (rol_id) REFERENCES public.roles(id);


--
-- Name: usuarios usuarios_tipo_sangre_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: angelfrederickpizaojeda
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_tipo_sangre_id_fkey FOREIGN KEY (tipo_sangre_id) REFERENCES public.tipos_sangre(id);


--
-- PostgreSQL database dump complete
--

\unrestrict unN5JVHqQ12x6ipqLmwdpQonAgFRTgmTU9paGh4brUs87EmS5TKDNH9QdP0ObDB

