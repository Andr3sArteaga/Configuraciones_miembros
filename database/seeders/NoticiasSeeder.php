<?php

namespace Database\Seeders;

use App\Models\NoticiasIncendio;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NoticiasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Sample news data based on the opinion.com.bo structure
        // Using null for images to display the fire icon fallback
        $noticias = [
            [
                'title' => 'Gobierno reporta que Bolivia no tiene incendios activos',
                'url' => 'https://www.opinion.com.bo/articulo/pais/gobierno-reporta-que-bolivia-tiene-incendios-activos/20251028172000982601.html',
                'description' => 'El mensaje de Arce fue acompañado por una imagen que muestra las labores para combatir los incendios y el mensaje "¡No tenemos incendios activos!".',
                'image' => null,
                'date' => Carbon::create(2025, 10, 28, 17, 20),
            ],
            [
                'title' => 'Senamhi activa alertas por riesgo de propagación de incendios',
                'url' => 'https://www.opinion.com.bo/articulo/pais/senamhi-activa-alertas-riesgo-propagacion-incendios/20250930000009980831.html',
                'description' => 'El Servicio Nacional de Meteorología e Hidrología activó alertas en varios municipios del país por el alto riesgo de propagación de incendios forestales.',
                'image' => null,
                'date' => Carbon::create(2025, 9, 30, 0, 0),
            ],
            [
                'title' => 'Activan alerta Roja y Naranja en 35 municipios del país por riesgo de propagación de incendios',
                'url' => 'https://www.opinion.com.bo/articulo/pais/activan-alerta-roja-naranja-35-municipios-pais-riesgo-propagacion-incendios/20250929185621980788.html',
                'description' => 'Autoridades activaron alertas de diferentes niveles en 35 municipios debido al alto riesgo de incendios forestales.',
                'image' => null,
                'date' => Carbon::create(2025, 9, 29, 18, 56),
            ],
            [
                'title' => 'FFAA combaten 10 incendios activos en Bolivia',
                'url' => 'https://www.opinion.com.bo/articulo/pais/ffaa-combaten-10-incendios-activos-bolivia/20250926185056980698.html',
                'description' => 'Se tiene nuevos fuegos activos en diferentes municipios cruceños, entre ellos en El Puente, San José de Chiquitos, Ascensión de Guarayos.',
                'image' => null,
                'date' => Carbon::create(2025, 9, 26, 18, 50),
            ],
            [
                'title' => 'Cochabamba registró 4 incendios forestales en un día',
                'url' => 'https://www.opinion.com.bo/articulo/cochabamba/cochabamba-registro-4-incendios-solo-dia-quema-pajonales-deja-fallecido/20250922001720980382.html',
                'description' => 'Se trata de un adulto mayor de 82 años, identificado como José M.P, quien habría sido atrapado por las llamas.',
                'image' => null,
                'date' => Carbon::create(2025, 9, 22, 0, 17),
            ],
        ];

        foreach ($noticias as $noticia) {
            NoticiasIncendio::updateOrCreate(
                ['id' => md5($noticia['url'])],
                [
                    'title' => $noticia['title'],
                    'url' => $noticia['url'],
                    'description' => $noticia['description'],
                    'image' => $noticia['image'],
                    'date' => $noticia['date'],
                    'creado' => now(),
                ]
            );
        }

        $this->command->info('Sample news seeded successfully!');
    }
}
