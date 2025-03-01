export const getStatusColor = (status: string) => {
    switch (status.toLowerCase()) {
      case 'pending':
      case 'en attente':
        return 'bg-yellow-100 text-yellow-800';
      case 'processing':
      case 'en cours':
        return 'bg-blue-100 text-blue-800';
      case 'shipped':
      case 'expédiée':
        return 'bg-purple-100 text-purple-800';
      case 'delivered':
      case 'livrée':
        return 'bg-green-100 text-green-800';
      case 'cancelled':
      case 'annulée':
        return 'bg-red-100 text-red-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };