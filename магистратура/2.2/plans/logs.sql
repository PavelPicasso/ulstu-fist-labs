-- phpMyAdmin SQL Dump
-- version 2.6.0
-- http://www.phpmyadmin.net
-- 
-- ����: localhost
-- �६� ᮧ�����: ��� 02 2007 �., 17:03
-- ����� �ࢥ�: 3.23.58
-- ����� PHP: 4.4.0
-- 
-- ��: `plans`
-- 

-- --------------------------------------------------------

-- 
-- ������� ⠡���� `logs`
-- 

CREATE TABLE `logs` (
  `id` int(11) NOT NULL auto_increment,
  `name_trans` varchar(20) NOT NULL default '',
  `id_sess` varchar(32) default NULL,
  `date_trans` datetime default NULL,
  `time_trans` float default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=42 ;

-- 
-- ���� ������ ⠡���� `logs`
-- 

INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (1, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:54:31', 0.00117373);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (2, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:54:33', 0.00106692);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (3, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:54:35', 0.00117588);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (4, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:56:39', 0.001194);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (5, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:57:57', 0.001194);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (6, '���樯����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:57:58', 18.3451);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (7, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:58:37', 0.00117874);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (8, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:59:30', 0.00532794);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (9, '�祡�� ����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:59:32', 0.60811);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (10, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:59:36', 0.005301);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (11, '�祡�� ����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:59:36', 0.59686);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (12, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:59:44', 0.00524807);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (13, '��ꥬ ����⨩', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:59:45', 0.603876);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (14, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 03:59:49', 0.00525117);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (15, '��⮪_���', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 04:00:16', 0.00247788);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (16, '��⮪�', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 04:01:47', 111.471);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (17, '��⮪_���', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 04:03:54', 0.00209689);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (18, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 04:03:55', 0.00523996);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (19, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 04:03:57', 0.00107789);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (20, '�㤨�', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 04:03:59', 106.424);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (21, '���_�����', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 04:05:53', 0.00109601);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (22, '����㧪�', '85785ee466c63b95f8fb4959d732276b', '2007-04-02 04:05:55', 147.855);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (23, '���_�����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:17:35', 0.00105524);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (24, '���_�����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:17:36', 0.00520992);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (25, '�祡�� ����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:17:38', 0.601875);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (26, '���_�����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:17:45', 0.00520396);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (27, '�祡�� ����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:17:46', 0.600919);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (28, '���_�����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:18:04', 0.00513601);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (29, '��ꥬ ����⨩', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:18:05', 0.60443);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (30, '���_�����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:34:55', 0.005162);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (31, '��⮪_���', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:34:59', 0.00191402);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (32, '��⮪�', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:35:00', 111.667);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (33, '��⮪_���', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:51:16', 0.00194287);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (34, '���_�����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:51:17', 0.00522304);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (35, '���_�����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:51:19', 0.00109291);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (36, '�㤨�', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:51:20', 109.263);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (37, '���_�����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:53:33', 0.00113893);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (38, '����㧪�', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:53:34', 148.228);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (39, '���_�����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:57:26', 0.00108409);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (40, '���樯����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:57:27', 18.4752);
INSERT INTO `logs` (`id`, `name_trans`, `id_sess`, `date_trans`, `time_trans`) VALUES (41, '���_�����', '48fcecb4ecbb761c250a47a7817281fd', '2007-04-02 04:59:10', 0.00107193);
        