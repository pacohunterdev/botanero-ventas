import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Insumos from '../components/Insumos/Insumos'
import Categorias from '../components/Categorias/Categorias'
import RegistrarInsumo from '../components/Insumos/RegistrarInsumo'
import EditarInsumo from '../components/Insumos/EditarInsumo'
import Configurar from '../components/Configuracion/Configurar'
import RealizarOrden from '../components/Ordenar/RealizarOrden'
import Ordenar from '../components/Ordenar/Ordenar'
import Usuarios from '../components/Usuarios/Usuarios'
import RegistrarUsuario from '../components/Usuarios/RegistrarUsuario'
import EditarUsuario from '../components/Usuarios/EditarUsuario'
import Login from '../components/Usuarios/Login'
import Perfil from '../components/Usuarios/Perfil'
import CambiarPassword from '../components/Usuarios/CambiarPassword'
import Inicio from '../components/Inicio'
import ReporteVentas from '../components/Ventas/ReporteVentas'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Inicio',
      component: Inicio
    },
    {
      path: '/insumos',
      name: 'Insumos',
      component: Insumos
    },
    {
      path: '/configurar',
      name: 'Configurar',
      component: Configurar
    },
    {
      path: '/realizar-orden',
      name: 'RealizarOrden',
      component: RealizarOrden
    },
    {
      path: '/ordenar/:id',
      name: 'Ordenar',
      component: Ordenar,
      props: true
    },
    
    {
      path: '/registrar-insumo',
      name: 'RegistrarInsumo',
      component: RegistrarInsumo
    },
    {
      path: '/categorias',
      name: 'Categorias',
      component: Categorias
    },
    {
      path: '/editar-insumo/:id',
      name: 'EditarInsumo',
      component: EditarInsumo,
    },
    {
      path: '/usuarios',
      name: 'Usuarios',
      component: Usuarios
    },
    {
      path: '/perfil',
      name: 'Perfil',
      component: Perfil
    },
    {
      path: '/cambiar-password',
      name: 'CambiarPassword',
      component: CambiarPassword
    },
    {
      path: '/registrar-usuario',
      name: 'RegistrarUsuario',
      component: RegistrarUsuario
    },
    {
      path: '/editar-usuario/:id',
      name: 'EditarUsuario',
      component: EditarUsuario,
    },
    {
      path: '/reporte-ventas',
      name: 'ReporteVentas',
      component: ReporteVentas
    },
  ]
})
