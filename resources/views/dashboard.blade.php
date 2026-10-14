<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">Daftar Pesanan Masuk</h2>
                <p class="text-xs text-gray-500 mt-0.5">Monitoring status transaksi tiket masuk</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wider hover:bg-indigo-700 shadow-sm transition">
                    Kelola Event
                </a>
                <a href="{{ route('customer.index') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wider hover:bg-slate-700 shadow-sm transition">
                    Lihat Event Customer
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if(session('success'))
            <div class="flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-lg shadow-sm">
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-200 text-slate-600 uppercase text-xs font-bold tracking-wider">
                            <th class="py-4 px-4 text-center"># ID</th>
                            <th class="py-4 px-4">Pelanggan</th>
                            <th class="py-4 px-4">Email</th>
                            <th class="py-4 px-4">Rincian Tiket</th>
                            <th class="py-4 px-4 text-right">Total Tagihan</th>
                            <th class="py-4 px-4 text-center">Status</th>
                            <th class="py-4 px-4 text-center">Aksi Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-4 text-center font-bold text-gray-500">#{{ $order->id }}</td>
                                <td class="py-4 px-4 font-semibold text-gray-900">{{ $order->customer_name }}</td>
                                <td class="py-4 px-4 text-gray-600 text-xs">{{ $order->customer_email }}</td>
                                <td class="py-4 px-4">
                                    <ul class="space-y-1">
                                        @foreach($order->orderDetails ?? $order->orderdetails as $detail)
                                            <li class="text-xs text-gray-700 flex items-center gap-1.5">
                                                <span class="font-semibold text-gray-900">{{ $detail->event->nama_konser ?? $detail->event->name ?? 'Tiket' }}</span>
                                                <span class="bg-slate-100 border border-slate-200 text-slate-700 px-1.5 py-0.2 rounded font-bold">x{{ $detail->quantity }}</span>
                                                <span class="text-gray-400">(Rp {{ number_format($detail->subtotal, 0, ',', '.') }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="py-4 px-4 text-right font-extrabold text-emerald-600 text-base">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @php $st = strtolower($order->status); @endphp
                                    @if($st == 'pending')
                                        <span class="bg-amber-100 text-amber-800 border border-amber-300 text-xs font-bold px-3 py-1 rounded-full">PENDING</span>
                                    @elseif(in_array($st, ['completed', 'confirmed', 'selesai']))
                                        <span class="bg-emerald-100 text-emerald-800 border border-emerald-300 text-xs font-bold px-3 py-1 rounded-full">SELESAI</span>
                                    @else
                                        <span class="bg-rose-100 text-rose-800 border border-rose-300 text-xs font-bold px-3 py-1 rounded-full">BATAL</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-300 rounded p-1.5 bg-white shadow-sm font-semibold focus:ring-blue-500 focus:border-blue-500">
                                            <option value="pending" {{ strcasecmp($order->status, 'pending') == 0 ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ strcasecmp($order->status, 'confirmed') == 0 ? 'selected' : '' }}>Selesai</option>
                                            <option value="cancelled" {{ strcasecmp($order->status, 'cancelled') == 0 ? 'selected' : '' }}>Batalkan</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-gray-400 font-medium">Belum ada pesanan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>