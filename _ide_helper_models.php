<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Barang
 *
 * @property int $id
 * @property string $nama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DetailVaksin[] $detail_vaksins
 * @property-read int|null $detail_vaksins_count
 * @method static \Illuminate\Database\Eloquent\Builder|Barang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Barang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Barang query()
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barang whereUpdatedAt($value)
 */
	class Barang extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DetailVaksin
 *
 * @property int $id
 * @property int $barang_id
 * @property string $kemasan
 * @property int $batch
 * @property string $tanggal
 * @property string $ed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Barang $barang
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaksi[] $transaksis
 * @property-read int|null $transaksis_count
 * @method static \Illuminate\Database\Eloquent\Builder|DetailVaksin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailVaksin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailVaksin query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetailVaksin whereBarangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailVaksin whereBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailVaksin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailVaksin whereEd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailVaksin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailVaksin whereKemasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailVaksin whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetailVaksin whereUpdatedAt($value)
 */
	class DetailVaksin extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Transaksi
 *
 * @property int $id
 * @property int $detail_vaksin_id
 * @property string $tanggal
 * @property string|null $dokumen
 * @property string|null $dari
 * @property string|null $kepada
 * @property int|null $penerimaan
 * @property int|null $pengeluaran
 * @property string|null $petugas
 * @property string|null $penerima
 * @property string|null $hp
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DetailVaksin $detail_vaksin
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereDari($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereDetailVaksinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereKepada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi wherePenerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi wherePenerimaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi wherePengeluaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi wherePetugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaksi whereUpdatedAt($value)
 */
	class Transaksi extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User.
 *
 * @property int $id
 * @property string $nip
 * @property string $nama
 * @property string $username
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

