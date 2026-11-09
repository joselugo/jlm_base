<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_selectores extends Model
{
    var $db1;
    function __construct()
    {
        $db1 = \Config\Database::connect('default');
        $this->db1 = $db1;
    }



    function get_all_servicios_by_cliente($id_cliente)
    {
        $builder = $this->db1->table('tblservicios t');
        $builder->select('t.*,p.*,s.nodo as server,t.id as id_servicio');
        $builder->join('perfiles p', 'p.id = t.idperfil');
        $builder->join('server s', 's.id = t.nodo');
        $builder->where('t.idcliente', $id_cliente);
        $builder->orderBy('t.id DESC');
        $query = $builder->get();
        if ($query != false) return $query->getResult();
        else return false;
    }

    function get_all_socios($permiso)
    {
        $builder = $this->db1->table('socios so');
        $builder->select('so.*');
        $builder->join("sindicato si", "si.id=so.sindicato");
        $builder->where($permiso);
        $builder->orderBy('so.id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->nombre . " " . $items->apellidos . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }


    function get_all_sucursales()
    {
        $builder = $this->db1->table('sucursal');
        $builder->select('*');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->sucursal . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }


    function get_all_sucursales_by_user($where)
    {
        $builder = $this->db1->table('sucursal');
        $builder->select('*');
        $builder->where($where);
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->sucursal . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_all_nodos()
    {
        $builder = $this->db1->table('server');
        $builder->select('*');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->nodo . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_all_lideres()
    {
        $builder = $this->db1->table('login');
        $builder->select('*');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->nombre . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_all_sindicatos_by_user($where)
    {
        $builder = $this->db1->table('sindicato');
        $builder->select('*');
        $builder->where($where);
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->nombre . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_all_roles()
    {
        $builder = $this->db1->table('rol');
        $builder->select('*');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->rol . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_all_tipo_documentos()
    {
        $builder = $this->db1->table('tipodocumento');
        $builder->select('*');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . strtoupper($items->nombre) . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_all_unidad_medida_tools()
    {
        $builder = $this->db1->table('unidad_tools');
        $builder->select('*');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->unidad_medida . "'>" . $items->texto . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_all_categorias_tools()
    {
        $builder = $this->db1->table('categorias_tools');
        $builder->select('*');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->producto . "'>" . $items->producto . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_all_categorias_productos_tools()
    {
        $builder = $this->db1->table('productos');
        $builder->select('*');
        $builder->where('tipo', 'producto');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->producto . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }
    function get_all_categorias_productos_accesorios_tools()
    {
        $builder = $this->db1->table('productos');
        $builder->select('*');
        $builder->where('tipo', 'accesorio');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->producto . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_all_proveedores_productos_tools()
    {
        $builder = $this->db1->table('proveedores');
        $builder->select('*');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->proveedor . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_all_zonas()
    {
        $builder = $this->db1->table('zonas');
        $builder->select('*');
        $builder->orderBy('zona', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->zona . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_all_pasarelas()
    {
        $builder = $this->db1->table('pasarelaotros');
        $builder->select('*');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->pasarela . "' data-dsp1='" . $items->dsp1 . "' data-dsp2='" . $items->dsp2 . "' data-dsp3='" . $items->dsp3 . "' data-dsp4='" . $items->dsp4 . "' >" . $items->pasarela . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }
    function get_all_operadores()
    {
        $builder = $this->db1->table('login l');
        $builder->select('l.id,l.username');
        $builder->orderBy('l.username', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->username . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    public function get_all_columns()
    {
        $builder = $this->db1->table('information_schema.columns');
        $builder->select('column_name');
        $builder->where('table_name', 'usuarios2');
        $builder->where('table_schema', 'Mikrowisp6');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->column_name . "'>" . $items->column_name . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    public function get_all_tables()
    {
        $builder = $this->db1->table('information_schema.tables');
        $builder->select('table_name');
        $builder->where('table_schema', 'Mikrowisp6');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->table_name . "'>" . $items->table_name . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_departamentos()
    {
        $builder = $this->db1->table('departamentos');
        $builder->select('id,dp');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->dp . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }
    function get_usuarios()
    {
        $builder = $this->db1->table('login');
        $builder->select('id,nombre,privilege');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            $option2 = "";
            foreach ($row as $items) {
                if ($items->privilege == 2) {
                    $option .= "<option value='" . $items->id . "'>" . $items->nombre . "</option>";
                }
                if ($items->privilege == 0) {
                    $option2 .= "<option value='" . $items->id . "'>" . $items->nombre . "</option>";
                }
            }
            $optgroup = "<optgroup label=\"TECNICOS\">$option</optgroup><optgroup label=\"ADMINISTRADORES\">$option2</optgroup>";
            return $optgroup;
        } else {
            return false;
        }
    }
    //05/01/2021 - funtion add
    public function get_usuarios_tecnicos()
    {
        $builder = $this->db1->table('login');
        $builder->select('id,nombre,privilege');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                if ($items->privilege == 2) {
                    $option .= "<option value='" . $items->id . "'>" . $items->nombre . "</option>";
                }
            }
            $optgroup = "<optgroup label=\"TECNICOS\">$option</optgroup>";
            return $optgroup;
        } else {
            return false;
        }
    }
    // fin
    public function get_productos_popup($idcategoria)
    {
        $builder = $this->db1->table('almacen');
        $builder->select('costo,serial_producto,mac_producto,id,item');
        $builder->where('productoid', $idcategoria);
        $builder->where('userid', '000000');
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }
    function get_producto_popup()
    {
        $builder = $this->db1->table('productos');
        $builder->select('id,descripcion,tipo,costo,clave_invoice,impuesto,producto');
        $builder->where('tipo', 'producto');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option data-des=$items->descripcion data-tipo=$items->tipo data-costo=$items->costo data-codigo=\"$items->clave_invoice\" data-impuesto=$items->impuesto value=$items->id >$items->producto</option>";
            }
            return $option;
        } else {
            return false;
        }
    }
    function get_producto_otro_popup()
    {
        $builder = $this->db1->table('productos');
        $builder->select('id,descripcion,tipo,costo,clave_invoice,impuesto,producto');
        $builder->where('tipo', 'otro');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option data-des=$items->descripcion data-tipo=\"otros\" data-costo=$items->costo data-codigo=\"$items->clave_invoice\" data-impuesto=$items->impuesto value=$items->id >$items->producto</option>";
            }
            return $option;
        } else {
            return false;
        }
    }
    public function get_anios_operacion()
    {
        $builder = $this->db1->table('operaciones');
        $builder->select('year(fecha_pago) as anio');
        $builder->groupBy('year(fecha_pago)');
        $builder->orderBy('year(fecha_pago)', 'DESC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $key => $items) {
                if ($key == 0) {
                    $option .= "<option value=\"$items->anio\" selected>$items->anio</option>";
                }
                $option .= "<option value=\"$items->anio\">$items->anio</option>";
            }
            return $option;
        } else {
            return false;
        }
    }
    function get_all_nodos_by_usuario($where)
    {
        $builder = $this->db1->table('server s');
        $builder->select('s.id,s.nodo');
        $builder->join('tblsucursalesnodo t', 't.idnodo=s.id');
        $builder->where($where);
        $builder->orderBy('s.id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->nodo . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_all_antenas_amisoras()
    {
        $builder = $this->db1->table('emisores e');
        $builder->select('id,nombre');
        $builder->orderBy('e.id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->nombre . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    public function get_planes_by_nodo($nodo)
    {
        $builder = $this->db1->table('perfiles');
        $builder->select('id,plan,costo,descripcion');
        $builder->where('id_mikrotik', $nodo);
        $builder->orWhere('id_mikrotik', "%%");
        $builder->orWhere('SUBSTRING_INDEX(id_mikrotik, ",",1)', $nodo);
        $builder->orWhere('SUBSTRING_INDEX(id_mikrotik, ",",-1)', $nodo);
        $builder->orLike('id_mikrotik', "%,$nodo,");
        $builder->orderBy('id ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "<option></option>";
            foreach ($row as $items) {
                $descripcion = $items->descripcion;
                $plan = $items->plan;
                $costo = $items->costo;
                $id = $items->id;
                $option .= "<option value='" . $id . "' data-costo='" . $costo . "' data-descripcion='" . $descripcion . "'>$plan</option>";
            }
            return $option;
        } else {
            return false;
        }
    }
    public function get_perfiles_void()
    {
        $builder = $this->db1->table('perfilesvoip');
        $builder->select('id,costo,descripcion,plan');
        $builder->orderBy('id ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {

                $option .= "<option value=\"$items->id\" data-costo=\"$items->costo\" data-descripcion=\"$items->descripcion\">$items->plan</option>";
            }
            return $option;
        } else {
            return false;
        }
    }
    public function get_nodos_por_sucursal($idsucursal)
    {
        $builder = $this->db1->table('tblsucursalesnodo t');
        $builder->select('s.id,s.nodo');
        $builder->where('t.idsucursal', $idsucursal);
        $builder->join('server s', 's.id=t.idnodo');
        $builder->orderBy('id ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "<option></option>";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'>" . $items->nodo . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    public function get_productos_permiso_suscursal($idcategoria, $where)
    {
        $builder = $this->db1->table('almacen a');
        $builder->select('*');
        $builder->where('a.productoid', $idcategoria);
        $builder->where('a.userid', '000000');
        $builder->where('a.catid', 'producto');
        $builder->where('a.estado', 'disponible');
        $builder->where($where);
        $builder->orderBy('a.id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "'> N° mac: " . $items->mac_producto . " (MX$" . $items->costo . ")" . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    public function get_accesorios_permiso_suscursal($idcategoria, $where)
    {
        $builder = $this->db1->table('almacen a');
        $builder->select('a.*,p.producto,p.descripcion');
        $builder->join('productos p', 'p.id=a.productoid');
        $builder->where('a.productoid', $idcategoria);
        $builder->where('a.userid', '000000');
        $builder->where('a.catid', 'accesorio');
        $builder->where('a.estado', 'disponible');
        $builder->where($where);
        $builder->orderBy('a.id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option data-dsp='" . $items->descripcion . "' data-cantidad='" . $items->cantidad . "'  value='" . $items->id . "'>" . $items->producto . " (" . $items->cantidad . "  unidades)" . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }


    function get_modelos_olt()
    {
        $builder = $this->db1->table('olt_model');
        $builder->select('*');
        $builder->orderBy('id ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->id . "' data-image='" . $items->imagen . "'>" . $items->modelo  . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_modelos_ont($idolt)
    {
        $builder = $this->db1->table('gpon_model_service_profile');
        $builder->select('*');
        $builder->where('idolt', $idolt);
        $builder->orderBy('id ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->srv_profile . "' data-image='" . $items->imagen . "'>" . $items->modelo  . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_vlans_olt($idolt)
    {
        $builder = $this->db1->table('olt_vlan');
        $builder->select('*');
        $builder->where('idolt', $idolt);
        $builder->orderBy('id ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->vlan . "'>" . $items->vlan  . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_trafic_olt($idolt)
    {

        $builder = $this->db1->table('olt_trafic');
        $builder->select('*');
        $builder->where('idolt', $idolt);
        $builder->orderBy('id ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='" . $items->idtrafic . "'>" . $items->trafic  . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }
    function get_all_nodos_sms()
    {
        $builder = $this->db1->table('server');
        $builder->select('id,nodo');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='SERVER_" . $items->id . "'>" . $items->nodo . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }
    function get_all_emisores_sms()
    {
        $builder = $this->db1->table('emisores');
        $builder->select('id,nombre');
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $option .= "<option value='EMISOR_" . $items->id . "'>" . $items->nombre . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }
    public function get_planes()
    {
        $builder = $this->db1->table('perfiles');
        $builder->select('id,plan');
        $builder->orderBy('id ASC');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $plan = $items->plan;
                $id = $items->id;
                $option .= "<option value=\"$id\">$plan</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_tipo_streaming()
    {
        $builder = $this->db1->table('streaming_tipo');
        $builder->select('id,nombre,tipo');
        $builder->where('estatus', 'ACTIVO');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                ($items->tipo == 1) ? $tipo = "(PERSONAL)" : $tipo = "(COMPARTIDO)";
                $option .= "<option value='" . $items->id . "'>" . $items->nombre . " $tipo" . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_tipo_cuenta()
    {
        $builder = $this->db1->table('streaming_tipo');
        $builder->select('id,nombre,tipo,pin');
        $builder->where('estatus', 'ACTIVO');
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                ($items->tipo == 1) ? $tipo = "(PERSONAL)" : $tipo = "(COMPARTIDO)";
                $option .= "<option data-tipo=\"$items->tipo\" data-pin=\"$items->pin\" value='" . $items->id . "'>" . $items->nombre . " $tipo" . "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_cuentas_streaming($id)
    {
        $builder = $this->db1->table('streaming');
        $builder->select('*');
        $builder->where('estado', 1);
        $builder->where('total_cuentas >', 0);
        $builder->where('id_streaming', $id);
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $disponible = "(Disponible " . $items->total_cuentas . ")";
                $option .= "<option value='" . $items->id . "'>" . $items->correo . " " . $disponible .  "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }

    function get_clientes_streaming_asig($id)
    {
        $builder = $this->db1->table('streaming');
        $builder->select('*');
        $builder->where('id_streaming', $id);
        $builder->where('estado', 1);
        $builder->where('total_cuentas >', 0);
        $query = $builder->get();
        if ($query != false) {
            $row = $query->getResult();
            $option = "";
            foreach ($row as $items) {
                $disponible = "(Disponible " . $items->total_cuentas . ")";
                $option .= "<option value='" . $items->id . "'>" . $items->correo . " " . $disponible .  "</option>";
            }
            return $option;
        } else {
            return false;
        }
    }
}
