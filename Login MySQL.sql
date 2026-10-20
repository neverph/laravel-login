drop database if exists Login;
create database Login;
use Login;

create table Usuario (
  id int auto_increment primary key,
  Nombres char(50) not null,
  Correo char(50) unique,
  Passwordd char(20) not null,
  create_at datetime default current_timestamp,
  update_at datetime default current_timestamp );

insert Usuario(Nombres,Correo,Passwordd) values ('Omar Espinoza', 'omar@gmail.com', '123456');

delimiter //
create procedure sp_Usuario_Guardar(in _nombres char(50), in _correo char(30), in _passwordd char(20) )
	begin
		declare _count int;
        select count(*) into _count from Usuario where Correo = _correo;
        if ( _count = 0 ) then
			insert Usuario(Nombres,Correo,Passwordd) values ( _nombres, _correo, _passwordd );
		  else update Usuario set Passwordd = _passwordd where Correo = _correo;
		end if;
    end;
//

create procedure sp_Usuario_Insert( in _nombres char(50), in _correo char(30), in _passwordd char(20) )
	insert Usuario(Nombres,Correo,Passwordd) values ( _nombres, _correo, _passwordd );

create procedure sp_Usuario_Update( in _correo char(30), in _passwordd char(20) )
	update Usuario set Passwordd = _passwordd where Correo = _correo;

create procedure sp_Usuario_Login( in _correo char(30), in _passwordd char(20) )
	select * from Usuario where Correo = _correo and Passwordd = _passwordd;

create procedure sp_Usuario_RecuperarPassword( in _correo char(30) )
	select Passwordd from Usuario where Correo = _correo;
    
-- call sp_Usuario_Guardar('Ali Espinoza','ali@gmail.com', '123456' );
-- select * from usuario;

/*
.get -> login   -> vista registrar
.post -> login  -> iniciar sesión ( correo, passwordd )
                -> datos : id, nombres, correo, passwordd
                -> procedimiento -> sp_Usuario_Login

.get ->login/registrar  -> vista de registra
.post ->login/registrar -> datos enviados : nombres, correo, passwordd
                        -> reenviar a vista de login, dashboard, ...
                        -> procedimiento -> sp_Usuario_Guardar

.get ->login/recuperar  -> vista de registra
.post ->login/recuperar -> datos enviados : correo
                        -> reenviar a vista de validar código, passwordd,...
                        -> procedimiento -> sp_Usuario_Codigo -- "falta"

.get ->login/validar  -> vista de validar código
.post ->login/validar -> datos enviados : código
                        -> procedimiento -> sp_Usuario_Validar
                        -> reenviar a vista de cambiar passwordd,...

.get ->login/passwordd  -> vista de registra correo y passwordd cambiar
.post ->login/passwordd -> datos enviados : correo, passwordd 
                        -> reenviar a vista de login, dashboard,...
                        -> procedimiento -> sp_Usuario_UpdatePasswordd -- falta

notas : falta la tabla donde guardar los codigo generados (id, idUsario, codigo)
*/