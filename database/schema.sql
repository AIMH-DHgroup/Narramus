--
-- PostgreSQL database dump
--

\restrict BzkebLYbYeKJionUL40sI2YyXkjp6cTw5uR0Rw7MbF4hcym4fbSQGuscqNkfxn4

-- Dumped from database version 18.6 (Ubuntu 18.6-0ubuntu0.26.04.1)
-- Dumped by pg_dump version 18.6 (Ubuntu 18.6-0ubuntu0.26.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: narrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.narrations (
    id_dbname text,
    id integer NOT NULL,
    subject text,
    title text,
    "user" integer,
    id_row integer NOT NULL,
    copied_from text
);


--
-- Name: id_narration_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.id_narration_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: id_narration_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.id_narration_seq OWNED BY public.narrations.id;


--
-- Name: id_row_table_narrations; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.id_row_table_narrations
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: id_row_table_narrations; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.id_row_table_narrations OWNED BY public.narrations.id_row;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id integer NOT NULL,
    username character(500) NOT NULL,
    password character(500),
    email character(500),
    vrelinks character(1500)
);


--
-- Name: id_users_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.id_users_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: id_users_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.id_users_seq OWNED BY public.users.id;


--
-- Name: narrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.narrations ALTER COLUMN id SET DEFAULT nextval('public.id_narration_seq'::regclass);


--
-- Name: narrations id_row; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.narrations ALTER COLUMN id_row SET DEFAULT nextval('public.id_row_table_narrations'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.id_users_seq'::regclass);


--
-- Name: narrations narrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.narrations
    ADD CONSTRAINT narrations_pkey PRIMARY KEY (id_row);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- PostgreSQL database dump complete
--

\unrestrict BzkebLYbYeKJionUL40sI2YyXkjp6cTw5uR0Rw7MbF4hcym4fbSQGuscqNkfxn4

