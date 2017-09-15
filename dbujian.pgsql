--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.4
-- Dumped by pg_dump version 9.6.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: genidljk(); Type: FUNCTION; Schema: public; Owner: mandan
--

CREATE FUNCTION genidljk() RETURNS character
    LANGUAGE plpgsql
    AS $$
DECLARE
id_tmp int;
id char(7);
BEGIN
SELECT max(id_ljk) into id_tmp from tbljk_ujian;
if id_tmp IS NULL then id_tmp=1;
else id_tmp = id_tmp + 1;
end if;
CASE
when id_tmp <= 9  then RETURN CONCAT('000000',id_tmp);
when id_tmp >= 10 AND id_tmp <= 99  then id = CONCAT('00000',id_tmp);
when id_tmp >= 100 AND id_tmp <= 999  then id = CONCAT('0000',id_tmp);
when id_tmp >= 1000 AND id_tmp <= 9999  then id = CONCAT('000',id_tmp);
when id_tmp >= 10000 AND id_tmp <= 99999  then id = CONCAT('00',id_tmp);
when id_tmp >= 100000 AND id_tmp <= 999999  then id = CONCAT('0',id_tmp);
ELSE id = id_tmp;
END CASE;
RETURN id;
END;
$$;


ALTER FUNCTION public.genidljk() OWNER TO mandan;

--
-- Name: genidsoal(); Type: FUNCTION; Schema: public; Owner: mandan
--

CREATE FUNCTION genidsoal() RETURNS character
    LANGUAGE plpgsql
    AS $$
declare id_tmp int;
BEGIN
SELECT max(id_soal) into id_tmp from tbsoal;
if id_tmp IS NULL then id_tmp=1;
else id_tmp=id_tmp+1;
end if;
CASE
when id_tmp <= 9  then RETURN CONCAT('000000',id_tmp);
when id_tmp >= 10 AND id_tmp <= 99  then RETURN CONCAT('00000',id_tmp);
when id_tmp >= 100 AND id_tmp <= 999  then RETURN CONCAT('0000',id_tmp);
when id_tmp >= 1000 AND id_tmp <= 9999  then RETURN CONCAT('000',id_tmp);
when id_tmp >= 10000 AND id_tmp <= 99999  then RETURN CONCAT('00',id_tmp);
when id_tmp >= 100000 AND id_tmp <= 999999  then RETURN CONCAT('0',id_tmp);
ELSE RETURN id_tmp;
END CASE;
END;
$$;


ALTER FUNCTION public.genidsoal() OWNER TO mandan;

--
-- Name: genidujian(); Type: FUNCTION; Schema: public; Owner: mandan
--

CREATE FUNCTION genidujian() RETURNS character
    LANGUAGE plpgsql
    AS $$
declare id_tmp int;
BEGIN
SELECT max(id_ujian) into id_tmp from tbujian;
if id_tmp IS NULL then id_tmp=1;
else id_tmp=id_tmp+1;
end if;
CASE
when id_tmp <= 9  then RETURN CONCAT('000000',id_tmp);
when id_tmp >= 10 AND id_tmp <= 99  then RETURN CONCAT('00000',id_tmp);
when id_tmp >= 100 AND id_tmp <= 999  then RETURN CONCAT('0000',id_tmp);
when id_tmp >= 1000 AND id_tmp <= 9999  then RETURN CONCAT('000',id_tmp);
when id_tmp >= 10000 AND id_tmp <= 99999  then RETURN CONCAT('00',id_tmp);
when id_tmp >= 100000 AND id_tmp <= 999999  then RETURN CONCAT('0',id_tmp);
ELSE RETURN id_tmp;
END CASE;
END;
$$;


ALTER FUNCTION public.genidujian() OWNER TO mandan;

--
-- Name: hi_lo(numeric, numeric, numeric); Type: FUNCTION; Schema: public; Owner: mandan
--

CREATE FUNCTION hi_lo(a numeric, b numeric, c numeric, OUT hi numeric, OUT lo numeric) RETURNS record
    LANGUAGE plpgsql
    AS $$
BEGIN
 hi := GREATEST(a,b,c);
 lo := LEAST(a,b,c);
END; $$;


ALTER FUNCTION public.hi_lo(a numeric, b numeric, c numeric, OUT hi numeric, OUT lo numeric) OWNER TO mandan;

--
-- Name: militojam(bigint); Type: FUNCTION; Schema: public; Owner: mandan
--

CREATE FUNCTION militojam(mili bigint) RETURNS smallint
    LANGUAGE plpgsql
    AS $$
declare hasil smallint;
BEGIN
hasil = floor(mili/3600000);
return hasil;
END;
$$;


ALTER FUNCTION public.militojam(mili bigint) OWNER TO mandan;

--
-- Name: militomenit(bigint); Type: FUNCTION; Schema: public; Owner: mandan
--

CREATE FUNCTION militomenit(mili bigint) RETURNS smallint
    LANGUAGE plpgsql
    AS $$
declare hasil smallint;
BEGIN
hasil = (mili % 3600000)/60000;
return hasil;
END;
$$;


ALTER FUNCTION public.militomenit(mili bigint) OWNER TO mandan;

--
-- Name: stringtotime(integer, integer); Type: FUNCTION; Schema: public; Owner: mandan
--

CREATE FUNCTION stringtotime(jam integer, menit integer) RETURNS bigint
    LANGUAGE plpgsql
    AS $$
declare hasil bigint;
BEGIN
hasil = jam*3600000+menit*60000;
return hasil;
END;
$$;


ALTER FUNCTION public.stringtotime(jam integer, menit integer) OWNER TO mandan;

--
-- Name: strtotime(smallint, smallint); Type: FUNCTION; Schema: public; Owner: mandan
--

CREATE FUNCTION strtotime(jam smallint, menit smallint) RETURNS bigint
    LANGUAGE plpgsql
    AS $$
declare hasil bigint;
BEGIN
hasil = jam*3600000+menit*60000;
return hasil;
END; $$;


ALTER FUNCTION public.strtotime(jam smallint, menit smallint) OWNER TO mandan;

--
-- Name: timetostring(bigint); Type: FUNCTION; Schema: public; Owner: mandan
--

CREATE FUNCTION timetostring(mili bigint) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare hasil varchar(50);
jam smallint;
menit smallint;
ket_jam varchar(10);
ket_menit varchar(20);
BEGIN
jam = miliToJam(mili);
menit = miliToMenit(mili);
if jam = 0 then ket_jam='';
else ket_jam=concat(jam,' Jam');
end if;

if menit = 0 then ket_jam ='';
else ket_menit = concat(menit,' Menit');
end if;
hasil = concat(ket_jam,' ',ket_menit);
return hasil;
END;
$$;


ALTER FUNCTION public.timetostring(mili bigint) OWNER TO mandan;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: id_tmp; Type: TABLE; Schema: public; Owner: mandan
--

CREATE TABLE id_tmp (
    max text
);


ALTER TABLE id_tmp OWNER TO mandan;

--
-- Name: tbhasil_ujian; Type: TABLE; Schema: public; Owner: mandan
--

CREATE TABLE tbhasil_ujian (
    id_hasil integer NOT NULL,
    id_ujian integer NOT NULL,
    id_peserta integer NOT NULL,
    benar smallint NOT NULL,
    salah smallint NOT NULL,
    nilai numeric NOT NULL
);


ALTER TABLE tbhasil_ujian OWNER TO mandan;

--
-- Name: tbhasil_ujian_id_hasil_seq; Type: SEQUENCE; Schema: public; Owner: mandan
--

CREATE SEQUENCE tbhasil_ujian_id_hasil_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tbhasil_ujian_id_hasil_seq OWNER TO mandan;

--
-- Name: tbhasil_ujian_id_hasil_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mandan
--

ALTER SEQUENCE tbhasil_ujian_id_hasil_seq OWNED BY tbhasil_ujian.id_hasil;


--
-- Name: tbjawaban_ljk; Type: TABLE; Schema: public; Owner: mandan
--

CREATE TABLE tbjawaban_ljk (
    id integer NOT NULL,
    id_jawaban_ljk character varying(9) NOT NULL,
    id_soal character varying(7) NOT NULL,
    jawaban character varying(5) NOT NULL
);


ALTER TABLE tbjawaban_ljk OWNER TO mandan;

--
-- Name: tbjawaban_ljk_id_seq; Type: SEQUENCE; Schema: public; Owner: mandan
--

CREATE SEQUENCE tbjawaban_ljk_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tbjawaban_ljk_id_seq OWNER TO mandan;

--
-- Name: tbjawaban_ljk_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mandan
--

ALTER SEQUENCE tbjawaban_ljk_id_seq OWNED BY tbjawaban_ljk.id;


--
-- Name: tbljk_ujian; Type: TABLE; Schema: public; Owner: mandan
--

CREATE TABLE tbljk_ujian (
    id_ljk character varying(7) NOT NULL,
    id_ujian character varying(7) NOT NULL,
    id_peserta integer NOT NULL,
    id_jawaban_ljk character varying(9) NOT NULL
);


ALTER TABLE tbljk_ujian OWNER TO mandan;

--
-- Name: tbpeserta; Type: TABLE; Schema: public; Owner: mandan
--

CREATE TABLE tbpeserta (
    id_peserta integer NOT NULL,
    nm_peserta character varying(50) NOT NULL
);


ALTER TABLE tbpeserta OWNER TO mandan;

--
-- Name: tbpeserta_id_peserta_seq; Type: SEQUENCE; Schema: public; Owner: mandan
--

CREATE SEQUENCE tbpeserta_id_peserta_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tbpeserta_id_peserta_seq OWNER TO mandan;

--
-- Name: tbpeserta_id_peserta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mandan
--

ALTER SEQUENCE tbpeserta_id_peserta_seq OWNED BY tbpeserta.id_peserta;


--
-- Name: tbpeserta_ujian; Type: TABLE; Schema: public; Owner: mandan
--

CREATE TABLE tbpeserta_ujian (
    id integer NOT NULL,
    id_ujian integer NOT NULL,
    id_peserta integer NOT NULL
);


ALTER TABLE tbpeserta_ujian OWNER TO mandan;

--
-- Name: tbpeserta_ujian_id_seq; Type: SEQUENCE; Schema: public; Owner: mandan
--

CREATE SEQUENCE tbpeserta_ujian_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tbpeserta_ujian_id_seq OWNER TO mandan;

--
-- Name: tbpeserta_ujian_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mandan
--

ALTER SEQUENCE tbpeserta_ujian_id_seq OWNED BY tbpeserta_ujian.id;


--
-- Name: tbpilihan_ganda; Type: TABLE; Schema: public; Owner: mandan
--

CREATE TABLE tbpilihan_ganda (
    id integer NOT NULL,
    id_soal integer,
    isi_pilihan text,
    huruf character varying(5)
);


ALTER TABLE tbpilihan_ganda OWNER TO mandan;

--
-- Name: tbpilihan_ganda_id_seq; Type: SEQUENCE; Schema: public; Owner: mandan
--

CREATE SEQUENCE tbpilihan_ganda_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tbpilihan_ganda_id_seq OWNER TO mandan;

--
-- Name: tbpilihan_ganda_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mandan
--

ALTER SEQUENCE tbpilihan_ganda_id_seq OWNED BY tbpilihan_ganda.id;


--
-- Name: tbsoal; Type: TABLE; Schema: public; Owner: mandan
--

CREATE TABLE tbsoal (
    id_soal integer NOT NULL,
    isi_soal text NOT NULL,
    jawaban character varying(5)
);


ALTER TABLE tbsoal OWNER TO mandan;

--
-- Name: tbsoal_id_soal_seq; Type: SEQUENCE; Schema: public; Owner: mandan
--

CREATE SEQUENCE tbsoal_id_soal_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tbsoal_id_soal_seq OWNER TO mandan;

--
-- Name: tbsoal_id_soal_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mandan
--

ALTER SEQUENCE tbsoal_id_soal_seq OWNED BY tbsoal.id_soal;


--
-- Name: tbsoal_ujian; Type: TABLE; Schema: public; Owner: mandan
--

CREATE TABLE tbsoal_ujian (
    id integer NOT NULL,
    id_ujian integer NOT NULL,
    id_soal integer NOT NULL
);


ALTER TABLE tbsoal_ujian OWNER TO mandan;

--
-- Name: tbsoal_ujian_id_seq; Type: SEQUENCE; Schema: public; Owner: mandan
--

CREATE SEQUENCE tbsoal_ujian_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tbsoal_ujian_id_seq OWNER TO mandan;

--
-- Name: tbsoal_ujian_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mandan
--

ALTER SEQUENCE tbsoal_ujian_id_seq OWNED BY tbsoal_ujian.id;


--
-- Name: tbujian; Type: TABLE; Schema: public; Owner: mandan
--

CREATE TABLE tbujian (
    id_ujian integer NOT NULL,
    nm_ujian character varying(100) NOT NULL,
    durasi_ujian bigint
);


ALTER TABLE tbujian OWNER TO mandan;

--
-- Name: tbujian_id_ujian_seq; Type: SEQUENCE; Schema: public; Owner: mandan
--

CREATE SEQUENCE tbujian_id_ujian_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tbujian_id_ujian_seq OWNER TO mandan;

--
-- Name: tbujian_id_ujian_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: mandan
--

ALTER SEQUENCE tbujian_id_ujian_seq OWNED BY tbujian.id_ujian;


--
-- Name: tbhasil_ujian id_hasil; Type: DEFAULT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbhasil_ujian ALTER COLUMN id_hasil SET DEFAULT nextval('tbhasil_ujian_id_hasil_seq'::regclass);


--
-- Name: tbjawaban_ljk id; Type: DEFAULT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbjawaban_ljk ALTER COLUMN id SET DEFAULT nextval('tbjawaban_ljk_id_seq'::regclass);


--
-- Name: tbpeserta id_peserta; Type: DEFAULT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbpeserta ALTER COLUMN id_peserta SET DEFAULT nextval('tbpeserta_id_peserta_seq'::regclass);


--
-- Name: tbpeserta_ujian id; Type: DEFAULT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbpeserta_ujian ALTER COLUMN id SET DEFAULT nextval('tbpeserta_ujian_id_seq'::regclass);


--
-- Name: tbpilihan_ganda id; Type: DEFAULT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbpilihan_ganda ALTER COLUMN id SET DEFAULT nextval('tbpilihan_ganda_id_seq'::regclass);


--
-- Name: tbsoal id_soal; Type: DEFAULT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbsoal ALTER COLUMN id_soal SET DEFAULT nextval('tbsoal_id_soal_seq'::regclass);


--
-- Name: tbsoal_ujian id; Type: DEFAULT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbsoal_ujian ALTER COLUMN id SET DEFAULT nextval('tbsoal_ujian_id_seq'::regclass);


--
-- Name: tbujian id_ujian; Type: DEFAULT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbujian ALTER COLUMN id_ujian SET DEFAULT nextval('tbujian_id_ujian_seq'::regclass);


--
-- Data for Name: id_tmp; Type: TABLE DATA; Schema: public; Owner: mandan
--

COPY id_tmp (max) FROM stdin;
\N
\.


--
-- Data for Name: tbhasil_ujian; Type: TABLE DATA; Schema: public; Owner: mandan
--

COPY tbhasil_ujian (id_hasil, id_ujian, id_peserta, benar, salah, nilai) FROM stdin;
9	1	5	0	3	0
10	1	5	3	0	100
\.


--
-- Name: tbhasil_ujian_id_hasil_seq; Type: SEQUENCE SET; Schema: public; Owner: mandan
--

SELECT pg_catalog.setval('tbhasil_ujian_id_hasil_seq', 10, true);


--
-- Data for Name: tbjawaban_ljk; Type: TABLE DATA; Schema: public; Owner: mandan
--

COPY tbjawaban_ljk (id, id_jawaban_ljk, id_soal, jawaban) FROM stdin;
\.


--
-- Name: tbjawaban_ljk_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mandan
--

SELECT pg_catalog.setval('tbjawaban_ljk_id_seq', 1, false);


--
-- Data for Name: tbljk_ujian; Type: TABLE DATA; Schema: public; Owner: mandan
--

COPY tbljk_ujian (id_ljk, id_ujian, id_peserta, id_jawaban_ljk) FROM stdin;
\.


--
-- Data for Name: tbpeserta; Type: TABLE DATA; Schema: public; Owner: mandan
--

COPY tbpeserta (id_peserta, nm_peserta) FROM stdin;
1	Afwan
2	Antum
3	Archlinux
4	Manjaro
5	regiza dafma
6	Ubuntu
7	LXDE
8	Gnome
9	XFCE
11	pgsql
\.


--
-- Name: tbpeserta_id_peserta_seq; Type: SEQUENCE SET; Schema: public; Owner: mandan
--

SELECT pg_catalog.setval('tbpeserta_id_peserta_seq', 43, true);


--
-- Data for Name: tbpeserta_ujian; Type: TABLE DATA; Schema: public; Owner: mandan
--

COPY tbpeserta_ujian (id, id_ujian, id_peserta) FROM stdin;
1	1	2
2	1	11
3	1	6
4	1	8
5	2	11
6	2	8
7	1	5
\.


--
-- Name: tbpeserta_ujian_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mandan
--

SELECT pg_catalog.setval('tbpeserta_ujian_id_seq', 7, true);


--
-- Data for Name: tbpilihan_ganda; Type: TABLE DATA; Schema: public; Owner: mandan
--

COPY tbpilihan_ganda (id, id_soal, isi_pilihan, huruf) FROM stdin;
16	1	3	A
17	1	4	B
18	2	5	A
19	2	4	B
20	3	18	A
21	3	22	B
22	3	33	C
23	4	3	A
24	4	5	B
25	4	6	C
26	5	2	A
27	5	4	B
28	5	10	C
\.


--
-- Name: tbpilihan_ganda_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mandan
--

SELECT pg_catalog.setval('tbpilihan_ganda_id_seq', 28, true);


--
-- Data for Name: tbsoal; Type: TABLE DATA; Schema: public; Owner: mandan
--

COPY tbsoal (id_soal, isi_soal, jawaban) FROM stdin;
1	1 + 2 =	A
2	2 +3 =	A
3	9 + 9 =	A
4	3 + 3 =	C
5	1 + 9 =	C
\.


--
-- Name: tbsoal_id_soal_seq; Type: SEQUENCE SET; Schema: public; Owner: mandan
--

SELECT pg_catalog.setval('tbsoal_id_soal_seq', 5, true);


--
-- Data for Name: tbsoal_ujian; Type: TABLE DATA; Schema: public; Owner: mandan
--

COPY tbsoal_ujian (id, id_ujian, id_soal) FROM stdin;
1	1	2
2	2	3
3	1	4
4	1	5
\.


--
-- Name: tbsoal_ujian_id_seq; Type: SEQUENCE SET; Schema: public; Owner: mandan
--

SELECT pg_catalog.setval('tbsoal_ujian_id_seq', 4, true);


--
-- Data for Name: tbujian; Type: TABLE DATA; Schema: public; Owner: mandan
--

COPY tbujian (id_ujian, nm_ujian, durasi_ujian) FROM stdin;
1	ini ujian	4140000
2	fsdf	3600000
\.


--
-- Name: tbujian_id_ujian_seq; Type: SEQUENCE SET; Schema: public; Owner: mandan
--

SELECT pg_catalog.setval('tbujian_id_ujian_seq', 2, true);


--
-- Name: tbhasil_ujian tbhasil_ujian_pkey; Type: CONSTRAINT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbhasil_ujian
    ADD CONSTRAINT tbhasil_ujian_pkey PRIMARY KEY (id_hasil);


--
-- Name: tbjawaban_ljk tbjawaban_ljk_pkey; Type: CONSTRAINT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbjawaban_ljk
    ADD CONSTRAINT tbjawaban_ljk_pkey PRIMARY KEY (id);


--
-- Name: tbljk_ujian tbljk_ujian_pkey; Type: CONSTRAINT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbljk_ujian
    ADD CONSTRAINT tbljk_ujian_pkey PRIMARY KEY (id_ljk);


--
-- Name: tbpeserta tbpeserta_pkey; Type: CONSTRAINT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbpeserta
    ADD CONSTRAINT tbpeserta_pkey PRIMARY KEY (id_peserta);


--
-- Name: tbpeserta_ujian tbpeserta_ujian_pkey; Type: CONSTRAINT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbpeserta_ujian
    ADD CONSTRAINT tbpeserta_ujian_pkey PRIMARY KEY (id);


--
-- Name: tbpilihan_ganda tbpilihan_ganda_pkey; Type: CONSTRAINT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbpilihan_ganda
    ADD CONSTRAINT tbpilihan_ganda_pkey PRIMARY KEY (id);


--
-- Name: tbsoal tbsoal_pkey; Type: CONSTRAINT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbsoal
    ADD CONSTRAINT tbsoal_pkey PRIMARY KEY (id_soal);


--
-- Name: tbsoal_ujian tbsoal_ujian_pkey; Type: CONSTRAINT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbsoal_ujian
    ADD CONSTRAINT tbsoal_ujian_pkey PRIMARY KEY (id);


--
-- Name: tbujian tbujian_pkey; Type: CONSTRAINT; Schema: public; Owner: mandan
--

ALTER TABLE ONLY tbujian
    ADD CONSTRAINT tbujian_pkey PRIMARY KEY (id_ujian);


--
-- PostgreSQL database dump complete
--

