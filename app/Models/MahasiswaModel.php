<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MahasiswaModel extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;

    protected $fillable = ['nim', 'user_id', 'nama_lengkap', 'angkatan', 'no_telp', 'alamat', 'program_studi_id', 'foto_profile'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudiModel::class);
    }

    public function prestasi(): HasMany
    {
        return $this->hasMany(DetailPrestasiModel::class, 'mahasiswa_nim', 'nim');
    }

    public function pendaftaranLomba(): HasMany
    {
        return $this->hasMany(PendaftaranLombaModel::class, 'mahasiswa_nim', 'nim');
    }

    public function dosenPembimbing(): HasMany
    {
        return $this->hasMany(DosenPembimbingModel::class, 'mahasiswa_nim', 'nim');
    }

    public function rekomendasi(): HasMany
    {
        return $this->hasMany(RekomendasiLombaModel::class, 'mahasiswa_nim', 'nim');
    }

    public function sertifikasis()
    {
        return $this->hasMany(SertifikasiModel::class, 'mahasiswa_nim', 'nim');
    }

    // public function detailBidangKeahlian(): HasMany
    // {
    //     return $this->hasMany(DetailBidangKeahlianModel::class, 'mahasiswa_nim', 'nim');
    // }


    // Jika ingin akses langsung ke `keahlian` atau `pengalaman` tanpa detail:
    public function bidangKeahlian()
    {
        return $this->belongsToMany(
            BidangKeahlianModel::class,
            'detail_bidang_keahlian',
            'mahasiswa_nim',
            'id_keahlian',
            'nim',
            'id'
        );
    }

    public function pengalaman()
    {
        return $this->hasMany(PengalamanModel::class, 'mahasiswa_nim', 'nim');
    }

}

?>