<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\PenerimaBantuan;

#[Layout('components.layouts.app')]
class SearchPageNav extends Component
{
    public string $query = '';
    public $results = [];

    public function mount()
    {
        $this->query = request('query') ?? '';
        if ($this->query) {
            $this->doSearch();
        }
    }

    public function doSearch()
    {
        $this->results = PenerimaBantuan::with('penilaian')
            ->where('nama', 'like', "%{$this->query}%")
            ->orWhere('kode_penerima', 'like', "%{$this->query}%")
            ->orWhere('nik', 'like', "%{$this->query}%")
            ->orWhere('kontak', 'like', "%{$this->query}%")
            ->get();
    }

    public function resetSearch()
    {
        $this->query = '';
        $this->results = [];
    }

    public function render()
    {
        return view('livewire.search-page');
    }
}
