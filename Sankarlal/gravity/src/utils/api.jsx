export async function fetchWithAuth(url, options = {}) {
    let access = localStorage.getItem('access');
    let refresh = localStorage.getItem('refresh');
  
    options.headers = options.headers || {};
    if (access) {
      options.headers['Authorization'] = `Bearer ${access}`;
    }
  
    let response = await fetch(url, options);
  
    // If token expired, try to refresh
    if (response.status === 401 && refresh) {
      const refreshResponse = await fetch('http://localhost:8000/api/token/refresh/', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ refresh }),
      });
      const refreshData = await refreshResponse.json();
  
      if (refreshResponse.ok && refreshData.access) {
        localStorage.setItem('access', refreshData.access);
        localStorage.setItem('refresh', refreshData.refresh);
        options.headers['Authorization'] = `Bearer ${refreshData.access}`;
        response = await fetch(url, options);
      } else {
        window.location.href = '/login';
        throw new Error('Session expired. Please log in again.');
      }
    }
  
    return response;
  }
  