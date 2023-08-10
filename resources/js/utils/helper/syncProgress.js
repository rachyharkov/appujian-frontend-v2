import axios from "axios";

export default async function syncProgress(data) {
    // make api request to store data

    try {
        const response = await axios.post('/api/sync-progress', data);
        return response.data;
    } catch (error) {
        return error;
    }
}
