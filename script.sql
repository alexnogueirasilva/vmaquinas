create or replace table mail_queue
(
    id              int(11) unsigned auto_increment
        primary key,
    subject         varchar(255) default ''                  not null,
    body            text                                     not null,
    from_email      varchar(255) default ''                  not null,
    from_name       varchar(255) default ''                  not null,
    recipient_email varchar(255) default ''                  not null,
    recipient_name  varchar(255) default ''                  not null,
    sent_at         timestamp                                null,
    created_at      timestamp    default current_timestamp() not null,
    updated_at      timestamp    default current_timestamp() not null on update current_timestamp()
);

create or replace table tipos
(
    id   int unsigned auto_increment
        primary key,
    nome enum ('Desenvolvimento', 'Atendimento', 'Manutenção', 'Manutenção urgente') not null
);

create or replace table users
(
    id         int(11) unsigned auto_increment
        primary key,
    first_name varchar(255) default ''                  not null,
    last_name  varchar(255) default ''                  not null,
    email      varchar(255) default ''                  not null,
    password   varchar(255) default ''                  not null,
    forget     varchar(255)                             null,
    genre      varchar(10)                              null,
    datebirth  date                                     null,
    document   varchar(11)                              null,
    photo      varchar(255)                             null,
    status     varchar(50)  default 'registered'        not null comment 'registered, confirmed',
    created_at timestamp    default current_timestamp() not null,
    updated_at timestamp                                null on update current_timestamp(),
    constraint email
        unique (email)
);

create or replace table atividades
(
    id         int unsigned auto_increment
        primary key,
    titulo     varchar(100)                                        not null,
    descricao  text                                                not null,
    status     enum ('open', 'closed') default 'open'              null,
    tipo_id    int unsigned                                        not null,
    user_id    int unsigned                                        not null,
    created_at date                    default current_timestamp() null,
    update_at  date                    default current_timestamp() null,
    due_at     date                                                null,
    constraint FK_ATIVIDADES_TIPOS
        foreign key (tipo_id) references tipos (id),
    constraint FK_ATIVIDADES_USER
        foreign key (user_id) references users (id)
);

