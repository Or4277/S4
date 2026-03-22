using System.Threading;
using System.Threading.Tasks;
using Npgsql;

namespace Data;

public sealed class GameRepository : IGameRepository
{
    private readonly IDbConnectionFactory _connectionFactory;

    public GameRepository(IDbConnectionFactory connectionFactory)
    {
        _connectionFactory = connectionFactory;
    }

    public async Task<int> SaveGameAsync(
        string plateau,
        IReadOnlyList<GameMove> moves,
        IReadOnlyList<GameLine> lines,
        int lignesJ1,
        int lignesJ2,
        CancellationToken cancellationToken = default)
    {
        await using var connection = await _connectionFactory.CreateOpenConnectionAsync(cancellationToken);
        var npgsqlConnection = (NpgsqlConnection)connection;
        await using var transaction = await npgsqlConnection.BeginTransactionAsync(cancellationToken);

        await using var insertPartie = new NpgsqlCommand(
            "INSERT INTO parties (plateau, lignes_j1, lignes_j2) VALUES (@plateau, @lignes_j1, @lignes_j2) RETURNING id;",
            npgsqlConnection,
            transaction);
        insertPartie.Parameters.AddWithValue("plateau", plateau);
        insertPartie.Parameters.AddWithValue("lignes_j1", lignesJ1);
        insertPartie.Parameters.AddWithValue("lignes_j2", lignesJ2);

        var partieIdObj = await insertPartie.ExecuteScalarAsync(cancellationToken);
        var partieId = partieIdObj is int id ? id : 0;

        if (partieId > 0 && moves.Count > 0)
        {
            await using var insertMove = new NpgsqlCommand(
                "INSERT INTO joueur (partie_id, joueur, x, y, tour) VALUES (@partie_id, @joueur, @x, @y, @tour);",
                npgsqlConnection,
                transaction);
            var partieIdParam = insertMove.Parameters.AddWithValue("partie_id", partieId);
            var joueurParam = insertMove.Parameters.AddWithValue("joueur", string.Empty);
            var xParam = insertMove.Parameters.AddWithValue("x", 0);
            var yParam = insertMove.Parameters.AddWithValue("y", 0);
            var tourParam = insertMove.Parameters.AddWithValue("tour", 0);

            foreach (var move in moves)
            {
                joueurParam.Value = move.Joueur;
                xParam.Value = move.X;
                yParam.Value = move.Y;
                tourParam.Value = move.Tour;

                await insertMove.ExecuteNonQueryAsync(cancellationToken);
            }
        }

        if (partieId > 0 && lines.Count > 0)
        {
            await using var insertLine = new NpgsqlCommand(
                "INSERT INTO lignes (partie_id, joueur, points) VALUES (@partie_id, @joueur, @points);",
                npgsqlConnection,
                transaction);
            var partieIdParam = insertLine.Parameters.AddWithValue("partie_id", partieId);
            var joueurParam = insertLine.Parameters.AddWithValue("joueur", string.Empty);
            var pointsParam = insertLine.Parameters.AddWithValue("points", string.Empty);

            foreach (var line in lines)
            {
                joueurParam.Value = line.Joueur;
                pointsParam.Value = line.Points;

                await insertLine.ExecuteNonQueryAsync(cancellationToken);
            }
        }

        await transaction.CommitAsync(cancellationToken);
        return partieId;
    }
}
